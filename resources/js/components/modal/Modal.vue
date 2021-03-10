<template>
    <b-modal
        v-model="isComponentModalActive"
        has-modal-card
        trap-focus
        :destroy-on-hide="false"
        aria-role="dialog"
        aria-modal>
        <template #default="props">
            <form action="">
                <div class="modal-card" style="width: auto">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Подтвердите удаление пользователя</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isComponentModalActive = false"/>
                    </header>
                    <section class="modal-card-body">
                        <p class="is-danger">Вы действительно хотите удалить пользователя?</p>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isComponentModalActive = false" />
                    </footer>
                </div>
            </form>
        </template>
    </b-modal>
</template>

<script>
import EventBus from "../../events/eventBus";
export default {
    name: "Modal",
    data:() => ({
        isComponentModalActive: false,
    }),
    mounted() {
        EventBus.$on('delete-user-confirm', function() {
            console.log('triggered');
            this.isComponentModalActive = false;
            console.log(this.isComponentModalActive);
        });
    }
}
</script>

<style scoped>

</style>
