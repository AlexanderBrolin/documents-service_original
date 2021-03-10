<?php

namespace App\Services;

use App\Constants\FieldTypes;
use App\Models\Individual;
use Illuminate\Support\Str;

class FioService
{
    public function getNameFromResponse(array $fields) {
        $name = null;
        foreach ($fields as $fieldType => $item) {
            if (in_array($fieldType, FieldTypes::getNameTypes(), true)) {
                $name = $item['text'] ?? '';
            }
        }
        return Str::lower($name);
    }

    public function getSurnameFromResponse(array $fields) {
        $surname = null;
        foreach ($fields as $fieldType => $item) {
            if (in_array($fieldType, FieldTypes::getSurnameTypes(), true)) {
                $surname = $item['text'] ?? '';
            }
        }
        return Str::lower($surname);
    }

    public function getFioFromResponse(array $fields) {
        $fio = null;
        $name = null;
        $surname = null;
        $patronymic = null;
        foreach ($fields as $fieldType => $item) {
            if (in_array($fieldType, FieldTypes::getFioTypes(), true)) {
                $fio = $item['text'] ?? '';
            }
            if (in_array($fieldType, FieldTypes::getNameTypes(), true)) {
                $name = $item['text'];
            }
            if (in_array($fieldType, FieldTypes::getSurnameTypes(), true)) {
                $surname = $item['text'];
            }
            if (in_array($fieldType, FieldTypes::getPatronymicTypes(), true)) {
                $patronymic = $item['text'];
            }
        }

        if ($name && $surname && $patronymic) {
            $fio = $surname . ' ' . $name . ' ' . $patronymic;
        }

        return Str::lower($fio);
    }

    public function getPatronymicFromResponse(array $fields) {
        $patronymic = null;
        foreach ($fields as $fieldType => $item) {
            if (in_array($fieldType, FieldTypes::getPatronymicTypes(), true)) {
                $patronymic = $item['text'] ?? '';
            }
        }
        return Str::lower($patronymic);
    }

    public function getBirthDateFromResponse(array $fields) {
        $birthDate = null;
        $birthDay = null;
        $birthMonth = null;
        $birthYear = null;
        foreach ($fields as $fieldType => $item) {
            if (in_array($fieldType, FieldTypes::getBornFullDateTypes(), true)) {
                $birthDate = $item['text'] ?? '';
            }
            if ($fieldType === "day_of_birth") {
                $birthDay = $item['text'] ?? '';
            }
            if ($fieldType === "month_of_birth") {
                $birthMonth = $item['text'] ?? '';
            }
            if ($fieldType === "year_of_birth") {
                $birthYear = $item['text'] ?? '';
            }
        }
        if ($birthDay && $birthMonth && $birthYear) {
            $birthDate = $birthDay . '.' . $birthMonth . '.' . $birthYear;
        }
        return Str::lower($birthDate);
    }

    public function getIndividualName(Individual $individual)
    {
        $name = null;
        $docs = $individual->documents;
        foreach ($docs as $doc) {
            $fields = $doc->fields;
            foreach ($fields as $field) {
                if (in_array($field->type, FieldTypes::getNameTypes(), true)) {
                    $name = $field->value;
                }
            }
        }
        return Str::lower($name);
    }

    public function getIndividualSurname(Individual $individual)
    {
        $surname = null;
        $docs = $individual->documents;
        foreach ($docs as $doc) {
            $fields = $doc->fields;
            foreach ($fields as $field) {
                if (in_array($field->type, FieldTypes::getSurnameTypes(), true)) {
                    $surname = $field->value;
                }
            }
        }
        return Str::lower($surname);
    }

    public function getIndividualPatronymic(Individual $individual)
    {
        $patronymic = null;
        $docs = $individual->documents;
        foreach ($docs as $doc) {
            $fields = $doc->fields;
            foreach ($fields as $field) {
                if (in_array($field->type, FieldTypes::getPatronymicTypes(), true)) {
                    $patronymic = $field->value;
                }
            }
        }
        return Str::lower($patronymic);
    }

    public function getIndividualBirthDate(Individual $individual)
    {
        $birthDate = null;
        $birthDay = null;
        $birthMonth = null;
        $birthYear = null;
        $docs = $individual->documents;
        foreach ($docs as $doc) {
            $fields = $doc->fields;
            foreach ($fields as $field) {
                if (in_array($field->type, FieldTypes::getBornFullDateTypes(), true)) {
                    $birthDate = $field->value;
                }
                if ($field->type === "day_of_birth") {
                    $birthDay = $field->value;
                }
                if ($field->type === "month_of_birth") {
                    $birthMonth = $field->value;
                }
                if ($field->type === "year_of_birth") {
                    $birthYear = $field->value;
                }
            }
            if ($birthDay && $birthMonth && $birthYear) {
                $birthDate = $birthDay . '.' . $birthMonth . '.' . $birthYear;
            }
        }
        return Str::lower($birthDate);
    }

    public function getIndividualFio(Individual $individual)
    {
        $fio = null;
        $name = null;
        $surname = null;
        $patronymic = null;
        $docs = $individual->documents;
        foreach ($docs as $doc) {
            $fields = $doc->fields;
            foreach ($fields as $field) {
                if (in_array($field->type, FieldTypes::getFioTypes(), true)) {
                    $fio = $field->value;
                }
                if (in_array($field->type, FieldTypes::getNameTypes(), true)) {
                    $name = $field->value;
                }
                if (in_array($field->type, FieldTypes::getSurnameTypes(), true)) {
                    $surname = $field->value;
                }
                if (in_array($field->type, FieldTypes::getPatronymicTypes())) {
                    $patronymic = $field->value;
                }
            }

            if ($name && $surname && $patronymic) {
                $fio = $surname . ' ' . $name . ' ' . $patronymic;
            }
        }
        return Str::lower($fio);
    }
}
