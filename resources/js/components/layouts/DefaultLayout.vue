<template>
    <div class="card">
        <div class="card-header d-block">
            <h2 class="title is-4 text-center py-3">
                <slot name="title"></slot>
            </h2>
            <h2 class="subtitle text-center text-primary">
                <slot name="subtitle"></slot>
            </h2>
        </div>
        <div class="card-content">
            <div class="">
                <slot name="content"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DefaultLayout"
}
</script>

<style scoped>

</style>
