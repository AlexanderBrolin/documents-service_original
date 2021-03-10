<template>
    <DefaultLayout>
        <template v-slot:title>
            Загрузка документов
        </template>
        <template v-slot:content>
            <b-steps
                v-model="activeStep"
                :has-navigation="false"
            >
                <b-step-item
                    label="Загрузка документов"
                    step="1"
                    :clickable="false"
                    class="mt-4"
                >
                    <UploadDocuments
                        @upload-documents="uploadDocuments"
                        :loading="uploadLoading"
                    />
                </b-step-item>
                <b-step-item
                    label="Обработка"
                    step="2"
                    :clickable="false"
                    class="mt-4"
                >
                    <ClassificationComponent
                        :details="classifiedDetails"
                        @recognize="onRecognize"
                    />
                </b-step-item>
            </b-steps>
        </template>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import documentService from "../../services/document/documentService";
import EventBus from "../../events/eventBus";
import UploadDocuments from "./UploadDocuments";
import ClassificationComponent from "../classification/ClassificationComponent";
import roleMixin from "../../mixins/roleMixin";
export default {
    name: "DocumentsPage",
    data: () => ({
        activeStep: 0,
        uploadLoading: false,
        classifiedDetails: []
    }),
    mixins: [roleMixin],
    components: {
        DefaultLayout,
        UploadDocuments,
        ClassificationComponent
    },
    methods: {
        async uploadDocuments(files) {
            try {
                this.uploadLoading = true;
                const formData = new FormData();
                files.map(file => {
                    formData.append('documents[]', file);
                });
                const response = await documentService.classifyDocuments(formData);
                this.classifiedDetails = _.groupBy(response, 'task_id');
                console.log('classifiedDetails');
                console.log(this.classifiedDetails);
                this.uploadLoading = false;
                this.activeStep = 1;
            } catch (error) {
                this.uploadLoading = false;
                EventBus.$emit('error', error.message);
            }
        },
        async onRecognize(taskId) {
            try {
                await documentService.recognizeTask(taskId);
            } catch (error) {
                EventBus.$emit('error', error.message);
            }
        }
    },
}
</script>

<style scoped>
</style>
