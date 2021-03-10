<?php

namespace App\Services;

use App\Constants\FieldTypes;
use App\Exceptions\Individual\SuchIndividualAlreadyExistsException;
use App\Models\Document;
use Illuminate\Support\Str;

class IndividualService
{
    public static function checkIfIndividualExists(array $documentsPayload): void
    {
        foreach ($documentsPayload as $taskId => $item) {
            $findName = '';
            $findSurname = '';
            $findPatronymic = '';
            $findFio = '';
            foreach ($item['fields'] as $type => $field) {
                if (in_array($type, FieldTypes::getNameTypes(), true)) {
                    $findName = Str::lower($field['text']);
                }
                if (in_array($type, FieldTypes::getSurnameTypes(), true)) {
                    $findSurname = Str::lower($field['text']);
                }
                if (in_array($type, FieldTypes::getPatronymicTypes(), true)) {
                    $findPatronymic = Str::lower($field['text']);
                }
                if (in_array($type, FieldTypes::getFioTypes(), true)) {
                    $findFio = Str::lower($field['text']);
                }
            }

            $docs = Document::all();
            foreach ($docs as $findDoc) {
                $perc = 0;
                $percents = 0;
                $counter = 0;

                if (!$findFio) {
                    $docName = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getNameTypes())
                        ->where('value', 'like', $findName)
                        ->get()->first();
                    $docName = $docName ? Str::lower($docName->value) : null;

                    $docSurname = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getSurnameTypes())
                        ->where('value', 'like', $findSurname)
                        ->get()->first();
                    $docSurname = $docSurname ? Str::lower($docSurname->value) : null;

                    $docPatronymic = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getPatronymicTypes())
                        ->where('value', 'like', $findPatronymic)
                        ->get()->first();
                    $docPatronymic = $docPatronymic ? Str::lower($docPatronymic->value) : null;

                    $docFio = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getFioTypes())
                        ->where('value', 'like', $findSurname . ' ' . $findName . ' ' . $findPatronymic)
                        ->get()->first();
                    $docFio = $docFio ? Str::lower($docFio->value) : null;
                } else {
                    $docName = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getNameTypes())
                        ->where('value', 'like', explode(' ', $findFio)[1])
                        ->get()->first();
                    $docName = $docName ? Str::lower($docName->value) : null;

                    $docSurname = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getSurnameTypes())
                        ->where('value', 'like', explode(' ', $findFio)[0])
                        ->get()->first();
                    $docSurname = $docSurname ? Str::lower($docSurname->value) : null;

                    $docPatronymic = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getPatronymicTypes())
                        ->where('value', 'like', explode(' ', $findFio)[2])
                        ->get()->first();
                    $docPatronymic = $docPatronymic ? Str::lower($docPatronymic->value) : null;

                    $docFio = $findDoc
                        ->fields()
                        ->whereIn('type', FieldTypes::getFioTypes())
                        ->where('value', 'like', $findFio)
                        ->get()->first();
                    $docFio = $docFio ? Str::lower($docFio->value) : null;
                }

                if (!$findFio) {
                    if (!$docFio) {
                        if ($docName && $findName) {
                            ++$counter;
                            similar_text($docName, $findName, $perc);
                            $percents += $perc;
                        }

                        if ($docSurname && $findSurname) {
                            ++$counter;
                            similar_text($docSurname, $findSurname, $perc);
                            $percents += $perc;
                        }

                        if ($docPatronymic && $findPatronymic) {
                            ++$counter;
                            similar_text($docPatronymic, $findPatronymic, $perc);
                            $percents += $perc;
                        }
                    } else {
                        ++$counter;
                        similar_text($findSurname . ' ' . $findName . ' ' . $findPatronymic, $docFio, $perc);
                        $percents += $perc;
                    }
                } else if (!$docFio) {
                    ++$counter;
                    similar_text($findFio, $docSurname . ' ' . $docName . ' ' . $docPatronymic, $perc);
                    $percents += $perc;
                } else {
                    ++$counter;
                    similar_text($docFio, $findFio, $perc);
                    $percents += $perc;
                }

                $result = 0;
                if ($percents && $counter) {
                    if (
                        ($findFio && $docFio)
                        || ($findFio && !$docFio)
                    ) {
                        if ($counter === 1) {
                            $result = $percents / $counter;
                        }
                    }

                    if (
                        $findName
                        && $findSurname
                        && $findPatronymic
                        && $docFio
                        && $counter === 1
                    ) {
                        $result = $percents / $counter;
                    }

                    if (
                        $findPatronymic
                        && $findName
                        && $findSurname
                        && $docName
                        && $docSurname
                        && $docPatronymic
                        && $counter === 3
                    ) {
                        $result = $percents / $counter;
                    }

                    if ($result > 85) {
                        throw new SuchIndividualAlreadyExistsException(
                            $findDoc->individual->id
                        );
                    }
                }
            }
        }
    }
}
