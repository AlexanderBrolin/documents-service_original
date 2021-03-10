<?php

namespace App\Actions\Field;

use App\Constants\HistoryTypes;
use App\Exceptions\Field\FieldNotFoundException;
use App\Models\History;
use App\Repositories\Field\FieldRepositoryInterface;
use Illuminate\Support\Facades\Auth;

final class UpdateFieldByIdAction
{
    private FieldRepositoryInterface $fieldRepository;

    public function __construct(FieldRepositoryInterface $fieldRepository)
    {
        $this->fieldRepository = $fieldRepository;
    }

    public function execute(UpdateFieldByIdRequest $request): UpdateFieldByIdResponse
    {
        $field = $this->fieldRepository->findById($request->getFieldId());

        if (!$field) {
            throw new FieldNotFoundException();
        }

        if ($field->value !== $request->getFieldValue()) {
            $field->value = $request->getFieldValue();

            History::create([
                'type' => HistoryTypes::FIELD,
                'before' => $field->getDifference()['before'],
                'after' => $field->getDifference()['after'],
                'author_id' => Auth::id(),
                'field_id' => $field->id,
                'document_id' => $field->document->id,
                'individual_id' => $field->document->individual->id,
            ]);
            $this->fieldRepository->save($field);
//            $fHistory->author()->associate(Auth::id());
//            $fHistory->field()->associate($field);
//            $fHistory->document()->associate($field->document->id);
//            $fHistory->individual()->associate($field->document->individual->id);
        }

        return new UpdateFieldByIdResponse($field);
    }
}
