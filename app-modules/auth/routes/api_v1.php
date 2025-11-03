<?php

use Modules\Auth\Http\Controllers\PermissionController;
use Modules\Auth\Http\Controllers\RoleController;
use Modules\Auth\Http\Controllers\RolePermissionController;
use Modules\Auth\Http\Controllers\UserRoleController;


Route::prefix('/users/credentials')->group(function () {
});

Route::prefix('permissions')->group(function () {
    Route::get('/', [PermissionController::class, 'findAll']);
    Route::get('/{permission}', [PermissionController::class, 'findById']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::put('/{permission}', [PermissionController::class, 'update']);
    Route::patch('/{permission}', [PermissionController::class, 'updatePartial']);
    Route::patch('/restore/{permissionId}', [PermissionController::class, 'restore']);
    Route::delete('/{permission}/soft', [PermissionController::class, 'deleteSoft']);
    Route::delete('/{permission}/force', [PermissionController::class, 'deleteHard']);
});

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'findAll']);
    Route::get('/{role}', [RoleController::class, 'findById']);
    Route::post('/', [RoleController::class, 'store']);
    Route::put('/{role}', [RoleController::class, 'update']);
    Route::patch('/{role}', [RoleController::class, 'updatePartial']);
    Route::patch('/restore/{roleId}', [RoleController::class, 'restore']);
    Route::delete('/{role}/soft', [RoleController::class, 'deleteSoft']);
    Route::delete('/{role}/force', [RoleController::class, 'deleteHard']);

});

Route::prefix('/roles/permissions')->group(function () {
    Route::get('/', [RolePermissionController::class, 'findAll']);
    Route::get('/{rolePermission}', [RolePermissionController::class, 'findById']);
    Route::post('/', [RolePermissionController::class, 'store']);
    Route::put('/{rolePermission}', [RolePermissionController::class, 'update']);
    Route::patch('/{rolePermission}', [RolePermissionController::class, 'updatePartial']);
    Route::patch('/restore/{rolePermissionId}', [RolePermissionController::class, 'restore']);
    Route::delete('/{rolePermission}/soft', [RolePermissionController::class, 'deleteSoft']);
    Route::delete('/{rolePermission}/force', [RolePermissionController::class, 'deleteHard']);

});

Route::prefix('/users/roles')->group(function () {
    Route::get('/', [UserRoleController::class, 'findAll']);
    Route::get('/{userRole}', [UserRoleController::class, 'findById']);
    Route::post('/', [UserRoleController::class, 'store']);
    Route::put('/{userRole}', [UserRoleController::class, 'update']);
    Route::patch('/{userRole}', [UserRoleController::class, 'updatePartial']);
    Route::patch('/restore/{userRoleId}', [UserRoleController::class, 'restore']);
    Route::delete('/{userRole}/soft', [UserRoleController::class, 'deleteSoft']);
    Route::delete('/{userRole}/force', [UserRoleController::class, 'deleteHard']);
});
