<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Efund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eFundData', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ctrl_no', 11)->comment('Format: YYYY-MM-NNNN; Resets annually'); 
            $table->smallInteger('type')->default(0)->comment('Types[0] => New; [2] => Reavailment'); 
            $table->string('EmpID', 30);
            $table->double('loan_amount')->unsigned();
            $table->string('local_dir_line', 50)->nullable();
            $table->integer('endorser_id')->unsigned()->nullable();
            $table->integer('guarantor_id')->unsigned()->nullable();
            $table->double('interest')->unsigned();
            $table->smallInteger('terms_month')->unsigned();
            $table->double('total')->unsigned()->comment('Approved loan + interest')->nullable();
            $table->double('deductions')->unsigned();
            $table->boolean('approved')->nullable();
            $table->string('approved_by', 30)->nullable();
            $table->datetime('approved_at')->nullable();
            $table->smallInteger('status')->default(0);
            $table->timestamps();
        });
        echo '"eFundData" table created successfully.' . PHP_EOL;

        Schema::create('endorsers', function (Blueprint $table){
            $table->increments('id');
            $table->string('refno', 30);
            $table->integer('eFundData_id')->unsigned();
            $table->string('EmpID', 30);
            $table->datetime('approved_at')->nullable();

            $table->foreign('eFundData_id')->references('id')->on('eFundData')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        echo '"endorsers" table created successfully.' . PHP_EOL;

        Schema::create('guarantors', function (Blueprint $table){
            $table->increments('id');
            $table->string('refno', 30);
            $table->integer('eFundData_id')->unsigned();
            $table->string('EmpID', 30);
            $table->datetime('approved_at')->nullable();

            $table->foreign('eFundData_id')->references('id')->on('eFundData')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        echo '"guarantor" table created successfully.' . PHP_EOL;

        Schema::create('treasury', function (Blueprint $table){
            $table->increments('id');
            $table->integer('eFundData_id')->unsigned();
            $table->string('cv_no', 10);
            $table->date('cv_date');
            $table->date('check_released')->nullable();
            $table->integer('created_by')->unsigned();
            $table->timestamps();

            $table->foreign('eFundData_id')->references('id')->on('eFundData')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        echo '"treasury" table created successfully.' . PHP_EOL;

        Schema::create('deductions', function (Blueprint $table){
            $table->increments('id');
            $table->integer('eFundData_id')->unsigned();
            $table->date('date');
            $table->string('ar_no', 10);
            $table->double('amount')->unsigned();
            $table->double('balance');
            $table->integer('updated_by')->unsigned();
            $table->integer('updated_at');

            $table->foreign('eFundData_id')->references('id')->on('eFundData')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        echo '"guarantor" table created successfully.' . PHP_EOL;

        Schema::create('loan_limits', function (Blueprint $table){
            $table->increments('id');
            $table->string('keyword', 50);
            $table->string('rank_position', 50);
            $table->double('min_amount')->unsigned();
            $table->double('max_amount')->unsigned();
            $table->boolean('allow_over_max')->comment('Addl if maximum amount is strictly to be followed.');
        });
        echo '"loan_limits" table created successfully.' . PHP_EOL;

        Schema::create('general_settings', function (Blueprint $table){
            $table->increments('id');
            $table->string('name', 50);
            $table->string('value');
            $table->string('description');
            $table->string('helper');
            $table->string('data_type');
        });
        echo '"general_settings" table created successfully.' . PHP_EOL;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('general_settings');
        Schema::drop('loan_limits');
        Schema::drop('deductions');
        Schema::drop('treasury');
        Schema::drop('guarantors');
        Schema::drop('endorsers');
        Schema::drop('eFundData');
    }
}
