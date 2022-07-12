<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('Dashboard');

Route::middleware(['auth'])->group(function () {
    //Users Roles
    Route::get('/manage-roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('Manage roles');
    Route::get('/manage-roles-{id}', [\App\Http\Controllers\RoleController::class, 'editUser'])->name('Edit roles');
    Route::patch('/update-roles-{id}', [\App\Http\Controllers\RoleController::class, 'updateUser'])->name('Update roles');
    Route::post('/create-role', [\App\Http\Controllers\RoleController::class, 'createRole'])->name('create role');
    Route::delete('/destroy-role/{id}', [\App\Http\Controllers\RoleController::class, 'destroyRole'])->name('destroy role');

    //Operational Planning
    Route::get('operation-planning-activities', [\App\Http\Controllers\OperationalPlanningController::class, 'operationPlanning'])->name('OP Activities');
    Route::get('operation-planning-{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'ChildActivity'])->name('OP activity');
    Route::get('New-Jobcard-{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'NewJobCard'])->name('New job-card');
    Route::post('New-Jobcard', [\App\Http\Controllers\OperationalPlanningController::class, 'store']);
    Route::get('operation-planning/sign/activity-one/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'signatureOne'])->name('sign.stage1');
    Route::patch('operation-planning/sign/activity-one/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'updateStageOne']);
    Route::get('operation-planning/sign/activity-two/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'ViewStage'])->name('sign.stage2');
    Route::get('operation-planning/sign/activity-three/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'ViewStage'])->name('sign.stage3');
    Route::get('operation-planning/sign/activity-four/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'ViewStage'])->name('sign.stage4');
    Route::get('operation-planning/sign/activity-five/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'ViewStage'])->name('sign.stage5');
    Route::get('operation-planning/sign/activity-six/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'ViewStage'])->name('sign.stage6');
    Route::patch('operation-planning/sign/activity/{id}', [\App\Http\Controllers\OperationalPlanningController::class, 'updateAllOtherStages']);

    //Communication of approved Plans
    Route::get('Communication/activities', [\App\Http\Controllers\CommunicationController::class, 'Communication'])->name('Comm Activities');
    Route::get('Communication/activity/{id}', [\App\Http\Controllers\CommunicationController::class, 'ChildActivity'])->name('Comm activity');
    Route::get('Communication/sign/activity-seven/{id}', [\App\Http\Controllers\CommunicationController::class, 'ViewStage'])->name('sign.stage7');
    Route::patch('Communication/sign/activity/{id}', [\App\Http\Controllers\CommunicationController::class, 'updateCommunicationStages']);

    //Fruit Collection
    Route::get('Fruit-collection/activities', [\App\Http\Controllers\FruitCollectionController::class, 'FruitCollection'])->name('FC Activities');
    Route::get('Fruit-collection/activity/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'ChildActivity'])->name('FC activity');
    Route::get('Fruit-collection/sign/activity-eight/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'ViewStage'])->name('sign.stage8');
    Route::get('Fruit-collection/sign/activity-nine/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'LabelGunnyBag'])->name('sign.stage9');
    Route::patch('/save/tree-number/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'storeTreeNumber']);
    Route::delete('/destroy/tree-number/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'destroyTreeNumber'])->name('destroy.treeNumber');
    Route::get('/complete/labelling-gunning-bags/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'CompleteLabelGunnyBags'])->name('complete.labelling');
    Route::patch('Fruit-collection/sign/activity/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'updateStageOne']);
    Route::get('Fruit-collection/sign/activity-ten/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'FruitCollectionFromTree'])->name('sign.stage10');
    Route::patch('/save/collected-quantity/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'storeCollectedPerTree']);
    Route::get('Fruit-collection/sign/activity-eleven/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'FruitCollectionFromFarm'])->name('sign.stage11');
    Route::patch('/save/farm-quantity/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'storeCollectionFromFarm']);
    Route::get('Fruit-collection/sign/activity-twelve/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'FruitCollectionNurseryTransport'])->name('sign.stage12');
    Route::patch('/save/nursery-transport-quantity/{id}', [\App\Http\Controllers\FruitCollectionController::class, 'storeNurseryTransport']);
});

require __DIR__ . '/auth.php';
