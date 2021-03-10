<template>
    <div class="multiple-upload">
        <label class="dropzone"
               :class="{ 'dropzone-active' : dropzoneActive }"
               @drop.prevent="onDrop"
               @dragover.prevent="onDropzoneHover"
               @dragleave.prevent="onDropzoneLeave"
               for="file-uploader"
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
        >
        <div class="previews" v-if="imagesPreviews.length || filesPreviews.length">
            <div
                class="files-previews d-flex justify-content-center mb-3"
                v-if="filesPreviews.length"
            >
                <div class="file-preview mr-2" v-for="filePreview in filesPreviews">
                    {{ filePreview }}
                </div>
            </div>
            <div class="images-previews d-flex justify-content-center">
                <figure v-if="imagesPreviews.length"
                        class="image mt-0 mb-0 mr-1"
                        v-for="imagePreview in imagesPreviews">
                    <img :src="imagePreview" class="image-256x256" alt="imagePreview" />
                </figure>
            </div>
        </div>

        <div class="buttons d-flex justify-content-center" v-if="dropFiles.length">
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
                        @click="clearFiles"
                        :loading="loading"
                        :disabled="loading"
                    >
                        Очистить
                    </b-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UploadDocuments",
    data: () => ({
        dropFiles: [],
        uploadLoading: false,
        dropzoneActive: false,
        imagesPreviews: [],
        availableTypes: [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/pdf',
            'image/bmp',
            'image/tiff',
            'image/gif',
            'image/vnd.djvu',
            'image/djvu'
        ],
        filesPreviews: [],
        filesNames: []
    }),
    props: ['loading'],
    methods: {
        clearFiles() {
            this.dropFiles = [];
            this.imagesPreviews = [];
            this.filesPreviews = [];
            this.filesNames = [];
            this.dropzoneActive = false;
        },
        onChange(event) {
            this.addFiles(event.target.files);
        },
        addFiles(files) {
            [...files].map(file => {
                if(file.size < this.maxFileSize) {
                    if (this.availableTypes.includes(file.type)) {
                        if (!this.filesNames.includes(file.name)) {
                            this.dropFiles.push(file);
                        }
                    }
                }
            });
            this.makePreviews();
        },
        onDropzoneHover() {
            this.dropzoneActive = true;
        },
        onDropzoneLeave() {
            this.dropzoneActive = false;
        },
        onDrop(event) {
            this.addFiles(event.dataTransfer.files);
        },
        makePreviews() {
            const previews = [];
            const filesPreviews = [];
            this.dropFiles.map(file => {
                const reader = new FileReader();
                if (file.type.includes('image/') && (!file.type.includes('tiff') && !file.type.includes('djvu'))) {
                    reader.readAsDataURL(file);
                    reader.onloadend = function() {
                        previews.push(reader.result);
                    }
                } else {
                    filesPreviews.push(file.name);
                }
            });
            this.imagesPreviews = previews;
            this.filesPreviews = filesPreviews;
        },
        onUploadDocuments() {
            if (this.dropFiles.length) {
                this.$emit('upload-documents', this.dropFiles);
            }
        }
    },
    computed: {
        maxFileSize() {
            return 10000000; // 10 MB
        }
    },
}
</script>

<style scoped lang="scss">

.file-preview {
    background: lightblue;
    border: 1px solid blue;
    padding: 10px;
    border-radius: 40px;
}
</style>
