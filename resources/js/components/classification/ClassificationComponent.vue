<template>
    <div class="classification">
        <div v-for="(tasks, taskKey) in details" v-if="Object.keys(details).length">
            <div
                class="row mb-3"
                v-for="task in tasks"
            >
                <div class="col-md-6">
                    <img :src="'storage/' + task.document_path" alt="Document Crop image"/>
                </div>
                <div class="col-md-6">
                    <h2 class="subtitle font-weight-bold">
                        {{ getDocumentNameByKey(task.document_type) }}
                    </h2>
                    <div class="fields-data" v-if="recognizedDataExists(taskKey, task.id)">
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
                            v-for="(field, field_name) in recognizedData[taskKey][task.id].fields"
                        >
                            <div class="col-md-4 text-left">
                                {{ getFieldNameByKey(field_name) }}
                            </div>
                            <div class="col-md-4 text-right">
                                <b-field v-if="editing && editableId === taskKey + '=' + field_name">
                                    <b-input
                                        :value="field.text"
                                        v-model="recognizedData[taskKey][task.id].fields[field_name].text"
                                        :type="field.text.length > 15 ? 'textarea' : ''"
                                    />
                                </b-field>
                                <span v-else>{{ field.text }}</span>
                            </div>
                            <div class="col-md-3 text-right">
                                <span
                                    class="confidence-badge"
                                    :class="'confidence-' + getLevelOfConfidence(field.confidence)"
                                >
                                    {{ field.confidence.toFixed(2) }}
                                </span>
                            </div>
                            <div class="col-md-1">
                                <b-tooltip
                                    label="Сохранить"
                                    position="is-left"
                                    v-if="editing && editableId === taskKey + '=' + field_name"
                                >
                                    <b-button
                                        type="is-success"
                                        icon-left="save"
                                        @click="editing = false; editableId = ''"
                                    >
                                    </b-button>
                                </b-tooltip>
                                <b-tooltip
                                    label="Отредактировать"
                                    position="is-left"
                                    v-else
                                >
                                    <b-button
                                        @click="editing = true; editableId = taskKey + '=' + field_name"
                                        type="is-info"
                                        icon-left="pencil-alt"
                                    >
                                    </b-button>
                                </b-tooltip>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger"
                         v-if="!Object.keys(recognizableDocTypes).includes(task.document_type)"
                    >
                        Документ не может быть распознан!
                    </div>
                </div>
            </div>
            <div class="buttons d-flex justify-content-center mb-5">
                <div class="col-md-4 row">
                    <div
                        :class="recognizedData[taskKey].recognized ? 'col-md-12' : 'col-md-6'"
                        v-if="recognizedData[taskKey].recognized && !recognizedData[taskKey].saved"
                    >
                        <b-button
                            type="is-success"
                            expanded
                            @click="saveIndividual(taskKey)"
                            :loading="recognizedData[taskKey].loading"
                        >
                            Сохранить
                        </b-button>
                    </div>
                    <div
                        :class="!recognizedData[taskKey].recognized ? 'col-md-12' : 'col-md-6'"
                        v-if="!recognizedData[taskKey].recognized"
                    >
                        <b-button
                            type="is-info"
                            expanded
                            @click="onRecognizeByTaskKey(taskKey)"
                            :loading="recognizedData[taskKey].loading"
                        >
                            Распознать
                        </b-button>
                    </div>
                    <div
                        class="col-md-12"
                        v-if="recognizedData[taskKey].saved"
                    >
                        <b-button
                            type="is-primary"
                            expanded
                            @click="goToIndividual(taskKey)"
                        >
                            Перейти
                        </b-button>
                    </div>
                    <div class="col-md-12">
                        <b-button
                            type="is-danger"
                            expanded
                            v-if="recognizedData[taskKey].existing"
                            @click="goToExisting(recognizedData[taskKey].existing)"
                        >
                            К существующему лицу
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import documentService from "../../services/document/documentService";
import EventBus from "../../events/eventBus";
import documentTypes from "../../constants/documentTypes";
import individualsMixin from "../../mixins/individualsMixin";
export default {
    name: "ClassificationComponent",
    props: ['details'],
    mixins: [individualsMixin],
    data: () => ({
        recognizedData: {},
        recognizableDocTypes: documentTypes.recognizable,
        goTo: {},
        editableId: '',
        editing: false
    }),
    mounted() {
        const self = this;
        document.addEventListener('keypress', (event) => {
           if (event.key === 'Enter') {
               if (self.editing) {
                   self.editing = false;
                   self.editableId = '';
               }
           }
        });
    },
    watch: {
        details() {
            Object.keys(this.details).map(key => {
                if (!this.recognizedData[key]) {
                    this.recognizedData[key] = {};
                }
                this.details[key].map(task => {
                    this.recognizedData[key][task.id] = {};
                    this.recognizedData[key][task.id].loading = false;
                    this.recognizedData[key][task.id].id = task.id;
                });
                this.recognizedData[key].loading = false;
                this.recognizedData[key].recognized = false;
                this.recognizedData[key].saved = false;
            });
        }
    },
    methods: {
        async onRecognizeByTaskKey(taskKey) {
            try {
                this.changePropertyForTaskKey('loading', taskKey, true);
                const response = await documentService.getRecognizeResponseByTaskKey({
                    task_key: taskKey
                });
                this.changePropertyForTaskKey('loading', taskKey, false);

                const groupedResult = _.groupBy(response, 'task_id');
                const result = {};
                Object.keys(groupedResult).map(taskKey => {
                    if (!result[taskKey]) result[taskKey] = {};
                    Object.keys(groupedResult[taskKey]).map(index => {
                        const task = groupedResult[taskKey][index];
                        if (!result[taskKey][task.id]) result[taskKey][task.id] = {};
                        result[taskKey][task.id] = groupedResult[taskKey][index];
                    });
                });

                this.recognizedData = {
                    ...this.recognizedData,
                    [taskKey]: {
                        ...this.recognizedData[taskKey],
                        ...result[taskKey]
                    }
                };
                this.changePropertyForTaskKey('recognized', taskKey, true);
            } catch (error) {
                this.changePropertyForTaskKey('loading', taskKey, false);
                EventBus.$emit('error', error.message);
            }
        },
        goToIndividual(taskKey) {
            window.location.href = '/individuals/' + this.goTo[taskKey];
        },
        goToExisting(id) {
            window.location.href = '/individuals/' + id;
        },
        async saveIndividual(taskKey) {
            try {
                const payloadRecognizedData = {};
                if (this.recognizedData[taskKey].recognized && !this.recognizedData[taskKey].saved) {
                    Object.keys(this.recognizedData[taskKey]).map(task => {
                        if (!payloadRecognizedData[taskKey])
                            payloadRecognizedData[taskKey] = {};

                        if (Object.keys(this.recognizedData[taskKey][task]).length) {
                            if (!payloadRecognizedData[taskKey][task])
                                payloadRecognizedData[taskKey][task] = {};

                            payloadRecognizedData[taskKey][task] = {
                                id: this.recognizedData[taskKey][task].id,
                                fields: this.recognizedData[taskKey][task].fields,
                                document_type: this.recognizedData[taskKey][task].doc_type
                            };
                        }
                    });
                }
                this.changePropertyForTaskKey('loading', taskKey, true);
                const response = await documentService.saveIndividual({
                    payloadData: payloadRecognizedData
                });
                this.goTo = {
                    ...this.goTo,
                    [taskKey]: response[taskKey]
                };
                this.changePropertyForTaskKey('loading', taskKey, false);
                this.changePropertyForTaskKey('saved', taskKey, true);
                EventBus.$emit('success', 'Физическое лицо было добавлено!');
            } catch(error) {
                if (error.type === 'existing_individual') {
                    this.recognizedData[taskKey].existing = error.code;
                    console.log(this.recognizedData[taskKey]);
                }
                this.changePropertyForTaskKey('loading', taskKey, false);
                EventBus.$emit('error', error.message);
            }
        },
        getLevelOfConfidence(confidence) {
            if (confidence >= 0 && confidence < 0.49) return 'low';
            if (confidence >= 0.49 && confidence < 0.7) return 'middle';
            if (confidence > 0.7) return 'high';
        },
        recognizedDataExists(taskKey, taskId) {
            if (this.recognizedData[taskKey][taskId]) {
                if (this.recognizedData[taskKey][taskId].fields) {
                    return Object.keys(this.recognizedData[taskKey][taskId].fields).length;
                }
            }
            return false;
        },
        changePropertyForTaskKey(property, taskKey, value) {
            this.recognizedData = {
                ...this.recognizedData,
                [taskKey]: {
                    ...this.recognizedData[taskKey],
                    [property]: value
                }
            };
        }
    }
}
</script>

<style scoped>
</style>
