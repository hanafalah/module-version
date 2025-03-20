<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zahzah\LaravelSupport\Concerns\NowYouSeeMe;
use Zahzah\ModuleVersion\Models\Application\App;
use Zahzah\ModuleVersion\Models\Application\ModelHasApp;

return new class extends Migration
{
    use NowYouSeeMe;

    private $__table;

    public function __construct(){
        $this->__table = app(config('database.models.ModelHasApp', ModelHasApp::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = $this->__table->getTableName();
        if (!$this->isTableExists()){
            Schema::create($table_name, function (Blueprint $table) {
                $app = app(config('database.models.App', App::class));

                $table->id();
                $table->string('model_type',50)->nullable(false);
                $table->string('model_id',36)->nullable(false);
                $table->foreignIdFor($app::class)->nullable(false)
                      ->index()->constrained()->restrictOnDelete()
                      ->cascadeOnUpdate();
                $table->timestamps();
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
        Schema::dropIfExists($this->__table->getTableName());
    }
};
