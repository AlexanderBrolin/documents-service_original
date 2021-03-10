<template>
    <DefaultLayout>
        <template v-slot:title>
            {{ user.second_name + ' ' + user.first_name + ' ' + user.patronymic }}
        </template>
        <template v-slot:subtitle>
            {{ user.role[0].name }}
        </template>
        <template v-slot:content>
            <b-message type="is-warning">В разработке!
            </b-message>
        </template>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
export default {
    name: "DashboardComponent",
    props: ['user'],
    components: {
        DefaultLayout
    },
    data: () => ({
        newUser: {
            first_name: '',
            second_name: '',
            patronymic: '',
            email: '',
            password: '',
            role: ''
        },
        newDocument: {
            name: '',
            alias: '',
            type: ''
        }
    })
}
</script>

<style scoped>

</style>
