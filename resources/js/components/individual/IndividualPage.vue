<template>
    <DefaultLayout>
        <template v-slot:title>
            {{ getFullName(individual) }}
        </template>
        <template v-slot:content>
            <b-tabs>
                <b-tab-item label="Документы">
                    <div class="documents">
                        <div
                            class="document row mb-4"
                            v-for="document in individual.documents"
                            @key="document.id"
                            v-if="document.type"
                        >
                            <div class="col-md-5">
                                <img :src="'/storage/' + document.document_image.path" />
                                <div
                                    class="d-flex justify-content-center mt-4"
                                    v-if="isAvailableToDeleteDocument()"
                                >
                                    <div class="col-md-6">
                                        <b-button
                                            type="is-danger"
                                            expanded
                                            :loading="deleteLoading"
                                            @click="confirmDeletingDocument(document.id)"
                                        >
                                            Удалить
                                        </b-button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h2 class="subtitle m-0">
                                    <b>#{{ document.id }} {{ getDocumentNameByKey(document.type) }}</b>
                                </h2>
                                <p class="mb-2 text-black-50 font-weight-bold">
                                    Добавлено: {{ createdAt(document.created_at) }}
                                </p>
<!--                                <div v-if="Object.keys(recDocTypes).includes(document.type)">-->
                                <div v-if="document.fields.length && document.type !== 'not_document'">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-left">
                                            <b>Название поля</b>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <b>Значение</b>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <b>Уверенность</b>
                                        </div>
                                        <div class="col-md-1 text-right">

                                        </div>
                                    </div>
                                    <div
                                        class="row mb-2"
                                        v-for="field in document.fields"
                                    >
                                        <div class="col-md-4 d-flex align-items-center">
                                            {{ getFieldNameByKey(field.type) }}
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                                            <b-field v-if="editableId === field.id">
                                                <b-input
                                                    :value="field.value"
                                                    v-model="editableValue"
                                                    :type="field.value.length > 15 ? 'textarea' : ''"
                                                />
                                            </b-field>
                                            <span v-else>{{ field.value }}</span>
                                        </div>
                                        <div
                                            class="col-md-3 text-right d-flex align-items-center is-justify-content-flex-end">
                                        <span
                                            class="confidence-badge"
                                            :class="'confidence-' + getLevelOfConfidence(field.confidence)"
                                        >
                                            {{ field.confidence.toFixed(2) }}
                                        </span>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center is-justify-content-flex-end">
                                            <div>
                                                <b-tooltip
                                                    label="Сохранить"
                                                    position="is-left"
                                                    v-if="editing && editableId === field.id"
                                                >
                                                    <b-button
                                                        type="is-success"
                                                        icon-left="save"
                                                        :loading="editLoading"
                                                        @click="onSave(field)"
                                                    >
                                                    </b-button>
                                                </b-tooltip>
                                                <b-tooltip
                                                    label="Отредактировать"
                                                    position="is-left"
                                                    v-else
                                                >
                                                    <b-button
                                                        @click="onEdit(field.id, field.value)"
                                                        type="is-info"
                                                        icon-left="pencil-alt"
                                                    >
                                                    </b-button>
                                                </b-tooltip>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div v-if="!receivedDetails">
                        <div class="">
                            <label class="dropzone"
                                   :class="{ 'dropzone-active' : dropzoneActive }"
                                   for="file-uploader"
                                   @drop.prevent="onDrop"
                                   @dragover.prevent="onDropzoneHover"
                                   @dragleave.prevent="onDropzoneLeave"
                            >
                                <span class="dropzone-title">
                                    Выберите файлы для распознавания
                                </span>
                                <span class="dropzone-subtitle">или просто перетащите их сюда</span>
                            </label>
                            <div class="text-center user-select-none">
                                Форматы JPEG, PNG, BMP, TIFF, GIF, PDF, DJVU — весом&nbsp;до&nbsp;10&nbsp;МБ.<br>
                                Поддерживаются многостраничные файлы и&nbsp;распознавание&nbsp;нескольких&nbsp;документов в одном файле.
                            </div>
                            <input
                                id="file-uploader"
                                :accept="availableTypes"
                                multiple=""
                                type="file"
                                autocomplete="off"
                                tabindex="-1"
                                style="display: none;"
                                @change="onChange"
                            />
                        </div>
                        <div
                            class="files-previews d-flex justify-content-center mb-3"
                            v-if="filesPreviews.length"
                        >
                            <div class="file-preview mr-2" v-for="filePreview in filesPreviews">
                                {{ filePreview }}
                            </div>
                        </div>
                        <div class="buttons d-flex justify-content-center" v-if="documentFiles.length">
                            <div class="mt-3 row col-md-6">
                                <div class="col-md-6">
                                    <b-button
                                        type="is-primary"
                                        expanded
                                        @click="onUploadDocuments"
                                        :loading="loading"
                                        :disabled="loading"
                                    >
                                        Загрузить
                                    </b-button>
                                </div>
                                <div class="col-md-6">
                                    <b-button
                                        type="is-danger"
                                        expanded
                                        :loading="loading"
                                        :disabled="loading"
                                        @click="clearFiles"
                                    >
                                        Очистить
                                    </b-button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="" v-else>
                        <div
                            class="row mb-2"
                            v-for="task in details"
                            @key="task.id"
                        >
                            <div v-if="
                                individualDocumentTypes.includes(task.document_type)
                                &&
                                !docTypesDuplicates.includes(task.document_type)"
                            >
                                <p class="text-center">
                                    <b>У данного физического лица уже существует документ такого типа!<br>
                                        Заменить его на новый экземпляр?</b>
                                </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2 class="text-center subtitle">Текущий документ</h2>
                                        <img :src="'/storage/' + getDocumentImagePath(task.document_type)" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <h2 class="text-center subtitle">Новый документ</h2>
                                        <img :src="'/storage/' + task.document_path" alt="">
                                    </div>
                                </div>
                                <div class="buttons d-flex justify-content-center my-4">
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <b-button
                                                    type="is-warning"
                                                    @click="dontChange(task.id)"
                                                    :disabled="tasksLoading[task.id].loading"
                                                    expanded
                                                >
                                                    Не заменять
                                                </b-button>
                                            </div>
                                            <div class="col-md-6">
                                                <b-button
                                                    type="is-danger"
                                                    @click="replaceDocument(task.id, task.document_type)"
                                                    :loading="tasksLoading[task.id].loading"
                                                >
                                                    Заменить на новый!
                                                </b-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col-md-6">
                                    <img :src="'/storage/' + task.document_path" alt="">
                                </div>
                                <div class="col-md-6">
                                    <h2 class="subtitle">{{ getDocumentNameByKey(task.document_type) }}</h2>
                                    <div v-if="canBeUpload(task.document_type)">
                                        <p class="text-success mb-3" v-if="!canBeDuplicated(task.document_type)">
                                            <b>Физическое лицо не имеет документа такого типа, добавить?</b>
                                        </p>
                                        <b-button
                                            type="is-danger"
                                            @click="dontChange(task.id)"
                                            :disabled="tasksLoading[task.id].loading"
                                        >
                                            Отмена
                                        </b-button>
                                        <b-button
                                            type="is-success"
                                            @click="addDocument(task.id)"
                                            :loading="tasksLoading[task.id].loading"
                                        >
                                            Добавить
                                        </b-button>
                                        <b-button
                                            type="is-danger"
                                            v-if="forceAdd"
                                            @click="addDocument(task.id, true)"
                                            :loading="tasksLoading[task.id].loading"
                                        >
                                            Принудительно добавить
                                        </b-button>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        <div class="d-flex justify-content-center">-->
<!--                            <div class="col-md-3">-->
<!--                                <b-button-->
<!--                                    type="is-success"-->
<!--                                    @click="endUploadingDocuments"-->
<!--                                    expanded-->
<!--                                >-->
<!--                                    Готово!-->
<!--                                </b-button>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </b-tab-item>

                <b-tab-item label="История">
                    <History
                        :history-data="individual.history"
                        @doc-restored="loadIndividual"
                    />
                </b-tab-item>
            </b-tabs>
        </template>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import individualService from "../../services/individual/individualService";
import individualsMixin from "../../mixins/individualsMixin";
import documentService from "../../services/document/documentService";
import EventBus from "../../events/eventBus";
import datetimeMixin from "../../mixins/datetimeMixin";
import uploadDocumentsMixin from "../../mixins/uploadDocumentsMixin";
import documentTypes from "../../constants/documentTypes";
import History from "./History";

export default {
    name: "IndividualPage",
    components: {
        DefaultLayout,
        History
    },
    mixins: [individualsMixin, datetimeMixin, uploadDocumentsMixin],
    props: ['id'],
    data: () => ({
        recDocTypes: documentTypes.recognizable,
        individual: [],
        documentFiles: [],
        fileNames: [],
        filesPreviews: [],
        dropzoneActive: false,
        receivedDetails: false,
        details: [],
        loading: false,
        tasksLoading: {},
        editableId: '',
        editing: false,
        editableValue: '',
        editLoading: false,
        historyData: [],
        deleteLoading: false,
        forceAdd: false
    }),
    async mounted() {
        await this.loadIndividual();
        document.addEventListener('keypress', async (event) => {
            if (event.key === 'Enter') {
                if (this.editing && this.editableId) {
                    const field = this.findField(this.editableId);
                    if (field) {
                        if (field.value !== this.editableValue) {
                            await this.onSave(field);
                        } else {
                            this.editing = false;
                            this.editableId = '';
                            this.editableValue = '';
                        }
                    }
                }
            }
        });
    },
    watch: {
        details() {
            const result = {};
            this.details.map(task => {
                if (!result[task.id]) result[task.id] = {};
                result[task.id].loading = false;
            });
            this.tasksLoading = result;
        }
    },
    methods: {
        findField(fieldId) {
            const documents = this.individual.documents;
            let fieldFound = null;
            documents.map(document => {
                document.fields.map(field => {
                    if (field.id === fieldId) {
                        fieldFound = field;
                    }
                });
            });
            return fieldFound;
        },
        async onDeleteDocument(documentId) {
            try {
                this.deleteLoading = true;
                await documentService.deleteDocument(documentId);
                this.deleteLoading = false;
                await this.loadIndividual();
                EventBus.$emit('warning', 'Документ был успешно удален!');
            } catch (error) {
                this.deleteLoading = false;
                EventBus.$emit('error', error.message);
            }
        },
        confirmDeletingDocument(docId) {
            this.$buefy.dialog.confirm({
                title: 'Удаление документа',
                message: 'Вы уверенны что хотите <b>удалить</b> документ? Это действие нельзя будет отменить.',
                confirmText: 'Удалить документ',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => this.onDeleteDocument(docId)
            });
        },
        isAvailableToDeleteDocument() {
            return this.individual.documents.length > 1;
        },
        getDocumentImagePath(documentType) {
            const document = this.individual.documents.find(document => document.type === documentType);

            return document.document_image.path;
        },
        async loadIndividual() {
            try {
                const response = await individualService.getIndividualUserById(this.id);
                this.individual = response;
                this.historyData = response.history;
            } catch (error) {
                EventBus.$emit('error', error.message);
            }
        },
        onDrop(event) {
            this.addFiles(event.dataTransfer.files);
        },
        clearFiles() {
            this.filesPreviews = [];
            this.fileNames = [];
            this.documentFiles = [];
            this.dropzoneActive = false;
        },
        onDropzoneHover() {
            this.dropzoneActive = true;
        },
        onDropzoneLeave() {
            this.dropzoneActive = false;
        },
        onChange(event) {
            this.addFiles(event.target.files);
        },
        dontChange(taskId) {
            const index = this.details.findIndex(item => item.id === taskId);
            this.details = [
                ...this.details.slice(0, index),
                ...this.details.slice(index + 1)
            ];
            if (!this.details.length) this.endUploadingDocuments();
        },
        addFiles(files) {
            [...files].map(file => {
                if(file.size < this.maxFileSize) {
                    if (this.availableTypes.includes(file.type)) {
                        if (!this.fileNames.includes(file.name)) {
                            this.documentFiles.push(file);
                        }
                    }
                }
            });
            this.makePreviews();
        },
        makePreviews() {
            const filesPreviews = [];
            this.documentFiles.map(file => {
                filesPreviews.push(file.name);
            });
            this.filesPreviews = filesPreviews;
        },
        endUploadingDocuments() {
            this.clearFiles();
            this.receivedDetails = false;
        },
        makeTaskLoading(taskId, value) {
            this.tasksLoading = {
                ...this.tasksLoading,
                [taskId]: {
                    ...this.tasksLoading,
                    loading: value
                }
            };
        },
        async onUploadDocuments() {
            if (this.documentFiles.length) {
                try {
                    this.loading = true;
                    const formData = new FormData();
                    this.documentFiles.map(file => {
                        formData.append('documents[]', file);
                    });
                    this.details = await documentService.classifyDocuments(formData);
                    this.receivedDetails = true;
                    this.loading = false;
                } catch (error) {
                    this.loading = false;
                    EventBus.$emit('error', error.message);
                }
            }
        },
        async replaceDocument(taskId, docType) {
            try {
                let documentId = "";
                this.individual.documents.map(document => {
                    if (document.type === docType) {
                        documentId = document.id;
                    }
                });
                this.makeTaskLoading(taskId, true);
                await documentService.replaceDocument({
                    task_id: taskId,
                    document_id: documentId
                });
                EventBus.$emit('success', 'Документ был успешно заменен!');
                this.makeTaskLoading(taskId, false);
                this.dontChange(taskId);
                await this.loadIndividual();
            } catch (error) {
                this.makeTaskLoading(taskId, false);
                EventBus.$emit('error', error.message);
            }
        },
        async addDocument(taskId, force = false) {
            try {
                this.makeTaskLoading(taskId, true);
                await documentService.saveDocumentForIndividual({
                    task_id: taskId,
                    individual_id: this.individual.id,
                    force
                });
                this.forceAdd = false;
                this.makeTaskLoading(taskId, false);
                await this.loadIndividual();
                this.dontChange(taskId);
                EventBus.$emit('success', 'Документ был успешно добавлен!');
            } catch (error) {
                if (error.type === 'another_person_document') {
                    this.forceAdd = true;
                }
                this.makeTaskLoading(taskId, false);
                EventBus.$emit('error', error.message);
            }
        },
        onEdit(editId, fieldValue) {
            this.editing = true;
            this.editableId = editId;
            this.editableValue = fieldValue;
        },
        async onSave(field) {
            if (this.editableValue !== field.value) {
                try {
                    this.editLoading = true;
                    await documentService.updateField({
                        field_id: field.id,
                        new_value: this.editableValue
                    });
                    this.editLoading = false;
                    this.editing = false;
                    this.editableId = '';
                    await this.loadIndividual();
                    EventBus.$emit('success', 'Поле успешно обновлено!');
                } catch (error) {
                    this.editLoading = false;
                    this.editing = false;
                    this.editableId = '';
                    EventBus.$emit('error', error.message);
                }
            } else {
                this.editing = false;
                this.editableId = '';
            }
        }
    },
    computed: {
        individualDocumentTypes() {
            return this.individual.documents.map(document => document.type);
        },
        docTypesDuplicates() {
            return Object.keys(documentTypes.canBeDuplicated);
        }
    },
}
</script>

<style scoped>
</style>
