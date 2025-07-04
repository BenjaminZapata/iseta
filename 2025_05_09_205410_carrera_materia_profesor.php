
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Schema;

//return new class extends Migration
//{
    /**
     * Run the migrations.
     */
    //public function up(): void
    //{
        //Schema::create('carrera_asignatura_profesor', function (Blueprint $table) {
            //$table->integer('id_carrera');
            //$table->integer('id_asignatura');
            //$table->integer('id_profesor')->nullable(true);

            //$table->primary(['id_carrera', 'id_asignatura']);

            //$table->foreign('id_carrera')->references('id')->on('carreras');
            //$table->foreign('id_asignatura')->references('id')->on('asignaturas');
            //$table->foreign('id_profesor')->references('id')->on('profesores');

            //$table->timestamps();
        //});

        // Migrar los datos existentes de asignaturas.id_carrera a la tabla pivote
        //DB::table('asignaturas')
        //->whereNotNull('id_carrera')
        //->get()
        //->each(function ($asignatura) {
            //DB::table('carrera_asignatura_profesor')->insert([
                //'id_asignatura' => $asignatura->id,
                //'id_carrera' => $asignatura->id_carrera,
            //]);
        //});


    //}

    /**
     * Reverse the migrations.
     */
  //  public function down(): void
   // {
        //
    //}
//};
