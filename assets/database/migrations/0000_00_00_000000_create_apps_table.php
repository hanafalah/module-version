<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zahzah\LaravelSupport\Concerns\NowYouSeeMe;
use Zahzah\ModuleVersion\Models\Application\App;

return new class extends Migration
{   
    use NowYouSeeMe;

    private $__table;

    public function __construct(){
        $this->__table = app(config('database.models.App', App::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()){
            Schema::create($table_name, function (Blueprint $table) {
                $table->id();
                $table->string('uuid',36)->unique()->nullable(false);
                $table->string('name',100)->unique()->nullable(false);
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::table($table_name, function (Blueprint $table) use ($table_name){
                $table->foreignIdFor($this->__table::class,'parent_id')->nullable()->after('id')->index()
                      ->constrained($table_name,$this->__table->getKeyName())
                      ->cascadeOnUpdate()->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
};
