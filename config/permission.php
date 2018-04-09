<?php

return [

    'models' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    /*
     * By default all permissions will be cached for 24 hours unless a permission or
     * role is updated. Then the cache will be flushed immediately.
     */

    'cache_expiration_time' => 60 * 24,

    /*
     * When set to true, the required permission/role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_permission_in_exception' => false,

    /**
     * All permission list for seeding database
     */
    'list' => [
        \App\Enum\PermissionEnum::CREATE_USER,
        \App\Enum\PermissionEnum::UPDATE_USER,
        \App\Enum\PermissionEnum::DELETE_USER,
        \App\Enum\PermissionEnum::VIEW_USERS,

        \App\Enum\PermissionEnum::CREATE_CATEGORY,
        \App\Enum\PermissionEnum::UPDATE_CATEGORY,
        \App\Enum\PermissionEnum::DELETE_CATEGORY,

        \App\Enum\PermissionEnum::CREATE_COURSE,
        \App\Enum\PermissionEnum::UPDATE_COURSE,
        \App\Enum\PermissionEnum::DELETE_COURSE,
        \App\Enum\PermissionEnum::VIEW_COURSE,

        \App\Enum\PermissionEnum::DELETE_FEEDBACK,
        \App\Enum\PermissionEnum::VIEW_FEEDBACK,
        \App\Enum\PermissionEnum::CREATE_MODULE,
        \App\Enum\PermissionEnum::UPDATE_MODULE,
        \App\Enum\PermissionEnum::DELETE_MODULE,
        \App\Enum\PermissionEnum::VIEW_MODULE,

        \App\Enum\PermissionEnum::CREATE_LESSON,
        \App\Enum\PermissionEnum::UPDATE_LESSON,
        \App\Enum\PermissionEnum::DELETE_LESSON,
        \App\Enum\PermissionEnum::VIEW_LESSON,

        \App\Enum\PermissionEnum::CREATE_TEST,
        \App\Enum\PermissionEnum::UPDATE_TEST,
        \App\Enum\PermissionEnum::DELETE_TEST,
        \App\Enum\PermissionEnum::VIEW_TEST,

        \App\Enum\PermissionEnum::CREATE_QUESTION,
        \App\Enum\PermissionEnum::UPDATE_QUESTION,
        \App\Enum\PermissionEnum::DELETE_QUESTION,
        \App\Enum\PermissionEnum::VIEW_QUESTION,

        \App\Enum\PermissionEnum::CREATE_ANSWER,
        \App\Enum\PermissionEnum::UPDATE_ANSWER,
        \App\Enum\PermissionEnum::DELETE_ANSWER,
        \App\Enum\PermissionEnum::VIEW_ANSWER,

        \App\Enum\PermissionEnum::CREATE_TARGET,
        \App\Enum\PermissionEnum::DELETE_TARGET,
        \App\Enum\PermissionEnum::VIEW_TARGET,

        \App\Enum\PermissionEnum::CREATE_TYPE_LESSON,
        \App\Enum\PermissionEnum::VIEW_TYPE_LESSON,

        \App\Enum\PermissionEnum::DELETE_EVENT,
        \App\Enum\PermissionEnum::VIEW_EVENT,

        \App\Enum\PermissionEnum::VIEW_TYPE_EVENT,
    ]
];
