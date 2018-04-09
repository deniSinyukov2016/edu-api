<?php

namespace App\Enum;

class PermissionEnum extends Enum
{
    const CREATE_USER = 'create user';
    const UPDATE_USER = 'update user';
    const DELETE_USER = 'delete user';
    const VIEW_USERS = 'view users';
    const EDIT_USERS = 'edit users';

    const CREATE_CATEGORY = 'create category';
    const UPDATE_CATEGORY = 'update category';
    const DELETE_CATEGORY = 'delete category';

    const CREATE_COURSE = 'create course';
    const DELETE_COURSE = 'delete course';
    const UPDATE_COURSE = 'update course';
    const VIEW_COURSE = 'view course';


    const DELETE_FEEDBACK = 'delete feedback';
    const VIEW_FEEDBACK = 'view feedback';

    const CREATE_MODULE = 'create module';
    const UPDATE_MODULE = 'update module';
    const DELETE_MODULE = 'delete module';
    const VIEW_MODULE = 'view modules';

    const CREATE_LESSON = 'create lesson';
    const UPDATE_LESSON = 'update lesson';
    const DELETE_LESSON = 'delete lesson';
    const VIEW_LESSON = 'view lesson';

    const CREATE_TEST = 'create test';
    const UPDATE_TEST = 'update test';
    const DELETE_TEST = 'delete test';
    const VIEW_TEST = 'view test';

    const CREATE_QUESTION = 'create question';
    const UPDATE_QUESTION = 'update question';
    const DELETE_QUESTION = 'delete question';
    const VIEW_QUESTION = 'view question';

    const CREATE_ANSWER = 'create answer';
    const UPDATE_ANSWER = 'update answer';
    const DELETE_ANSWER = 'delete answer';
    const VIEW_ANSWER = 'view answer';


    const CREATE_TARGET = 'create target';
    const DELETE_TARGET = 'delete target';
    const VIEW_TARGET = 'view target';

    const CREATE_TYPE_LESSON = 'create type lesson';
    const VIEW_TYPE_LESSON = 'view type lesson';

    const DELETE_EVENT = 'delete event';
    const VIEW_EVENT = 'view event';

    const VIEW_TYPE_EVENT = 'view type event';
}
