<template>
    <div class="history">
        <div
            v-if="historyData.length"
            class="mb-3"
            v-for="history in historyData"
            @key="history.id"
        >
            <b-message :type="getMessageType(history.type)">
                <span class="author-name">
                    {{ history.author.first_name + ' ' + history.author.second_name + ' ' + history.author.patronymic }}
                </span>
                <span class="author-role">
                    ({{ history.author.role[0].name}})
                </span>
                <span v-if="history.type === 'field'">
                    отредактировал поле - <b>{{ getFieldNameByKey(history.field.type) }}</b>,
                    Документ <b>#{{ history.field.document_id }}</b>
                    До: <span class="text-success">{{ JSON.parse(history.before).value }}</span>,
                    После: <span class="text-danger">{{ JSON.parse(history.after).value }}</span>
                </span>
                <span v-if="history.type === 'document_add'">
                    добавил документ <b>#{{ history.document_id }}</b>
                </span>
                <div v-if="history.type === 'document_add'">
                    <div class="col-md-6">
                        <b-image
                            :src="'/storage/' + history.before"
                        ></b-image>
                    </div>
                </div>
                <span v-if="history.type === 'document_update'">
                    обновил документ <b>#{{ history.document_id }}</b>.
                </span>
                <span v-if="history.type === 'document_restore'">
                    восстановил документ <b>#{{ history.document_id }}</b>.
                </span>
                <div v-if="history.type === 'document_update'">
                    <div class="row col-md-8">
                        <div class="col-md-6 cursor-pointer" @click="beforeImageModal = true">
                            <span class="font-weight-bold">До</span>
                            <b-image
                                :src="'/storage/' + history.before"
                            ></b-image>
                        </div>
                        <div class="col-md-6 cursor-pointer" @click="afterImageModal = true">
                            <span class="font-weight-bold">После</span>
                            <b-image
                                :src="'/storage/' + history.after"
                            ></b-image>
                        </div>
                    </div>
                </div>
                <span v-if="history.type === 'document_delete'">
                    удалил документ <b>#{{ history.document_id }}</b>.
<!--                    <span-->
<!--                        class="text-info text-underline"-->
<!--                        @click="onRestoreDocument(history.document_id)"-->
<!--                    >-->
<!--                        Восстановить?-->
<!--                    </span>-->
                </span>
                <br>
                <small>
                    <b class="text-black-50">{{ createdAt(history.created_at) }}</b>
                </small>
            </b-message>
        </div>
        <div v-else>
            <b-message type="is-warning">История пуста!</b-message>
        </div>
    </div>

</template>

<script>
import datetimeMixin from "../../mixins/datetimeMixin";
import individualsMixin from "../../mixins/individualsMixin";
import documentService from "../../services/document/documentService";
import EventBus from "../../events/eventBus";

export default {
    name: "History",
    mixins: [datetimeMixin, individualsMixin],
    props: ['historyData'],
    data: () => ({
        beforeImageModal: false,
        afterImageModal: false,
    }),
    methods: {
        async onRestoreDocument(docId) {
            try {
                await documentService.restoreDocument({
                    id: docId
                });
                this.$emit('doc-restored');
                EventBus.$emit('success', 'Документ успешно восстановлен!');
            } catch (error) {
                EventBus.$emit('error', error.message);
            }
        },
        getMessageType(historyType) {
            if (historyType.includes('delete')) return 'is-warning';
            if (historyType.includes('restore')) return 'is-success';
            return 'is-info';
        }
    }
}
</script>

<style scoped>
.text-underline {
    text-decoration: underline;
}
</style>
