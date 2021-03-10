<template>
    <b-modal
        v-model="visibility"
        has-modal-card
        trap-focus
        :destroy-on-hide="false"
        aria-role="dialog"
        aria-modal>
        <template #default="props">
            <form action="">
                <div class="modal-card" style="width: auto">
                    <header class="modal-card-head">
                        <p class="modal-card-title mr-3">Подтвердите удаление пользователя</p>
                        <button
                            type="button"
                            class="delete"
                            @click="visibility = false"/>
                    </header>
                    <section class="modal-card-body">
                        <p class="is-danger">Вы действительно хотите удалить пользователя?</p>
                    </section>
                    <footer class="modal-card-foot has-text-right">
                        <b-button
                            label="Отмена"
                            @click="visibility = false" />
                        <b-button
                            label="Да"
                            type="is-danger"
                            @click="confirmDeleting" />
                    </footer>
                </div>
            </form>
        </template>
    </b-modal>
</template>

<script>
export default {
    name: "DeleteUserModalConfirmation",
    data: () => ({
    }),
    props: ['visible', 'id'],
    methods: {
        confirmDeleting() {
            this.$emit('delete-user', this.id);
        }
    },
    computed: {
        visibility: {
            get() {
                return this.visible;
            },
            set(value) {
                this.$emit('close');
            }
        }
    }
}
</script>

<style scoped>

</style>
