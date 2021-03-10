<template>
    <DefaultLayout>
        <template v-slot:title>
            Задания
        </template>
        <template v-slot:content>
            <b-loading :is-full-page="true" v-model="isLoading"></b-loading>
            <div class="row">
                <div v-for="task in tasks" @key="task.id" class="col-md-4 mb-2">
                    <b-message>
                        <b>#{{ task.id }}</b><br>{{ createdAt(task.created_at) }}<br>
                        <b>{{ authorName(task) }}</b>, <u>{{ task.author.role[0].name }}</u><br>
                        Cоздал задание на {{ taskName(task) }}<br>
                        <b>DBrain TaskId:</b><br>
                        <span class="text-danger">{{ task.task_id }}</span><br>
                        <b>Document Type:</b><br>
                        <span class="text-danger">{{ task.document_type }}</span>
                    </b-message>
                </div>
            </div>
        </template>
    </DefaultLayout>
</template>

<script>
import taskService from "../../services/task/taskService";
import DefaultLayout from "../layouts/DefaultLayout";
import datetimeMixin from "../../mixins/datetimeMixin";
export default {
    name: "TasksComponent",
    mixins: [datetimeMixin],
    components: {
        DefaultLayout
    },
    data: () => ({
        tasks: [],
        isLoading: true
    }),
    async mounted() {
        this.isLoading = true;
        this.tasks = await taskService.fetchTasks();
        this.isLoading = false;
    },
    methods: {
        taskName(task) {
            return task.type === 'classify' ? 'классификацию' : 'распознавание';
        },
        authorName(task) {
            return `${task.author.first_name} ${task.author.second_name} ${task.author.patronymic}`;
        }
    }
}
</script>

<style scoped>

</style>
