<template>
</template>

<script>
import EventBus from "../../events/eventBus";
import eventsList from "../../events/eventsList";
export default {
    name: "Toast",
    created() {
        EventBus.$on('manager-added', function () {
            this.$buefy.toast.open(eventsList['manager-added'])
        });
        EventBus.$on('user-deleted', function () {
            this.$buefy.toast.open(eventsList['user-deleted'])
        });
        EventBus.$on('user-blocked', function () {
            this.$buefy.toast.open(eventsList['user-blocked'])
        });
        EventBus.$on('user-unblocked', function () {
            this.$buefy.toast.open(eventsList['user-unblocked'])
        });
        EventBus.$on('error', function (message) {
            this.$buefy.toast.open(eventsList.error(message));
        });
        EventBus.$on('success', function (message) {
            this.$buefy.toast.open(eventsList.success(message));
        });
        EventBus.$on('warning', function (message) {
            this.$buefy.toast.open(eventsList.warning(message));
        });
    }
}
</script>

<style scoped>

</style>
