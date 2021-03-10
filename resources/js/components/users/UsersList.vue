<template>
    <DefaultLayout>
        <template v-slot:title>
            Пользователи
        </template>
        <template v-slot:content>
            <b-table
                :data="users"
                v-if="users.length"
                :bordered="false"
                :hoverable="true"
                :mobile-cards="true"
                sort-icon="arrow-up"
                :sort-icon-size="'is-small'"
            >
                <b-table-column
                    field="id"
                    label="ID"
                    width="40"
                    numeric
                    v-slot="props"
                    sortable
                >
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column
                    label="Роль"
                    width="40"
                    v-slot="props"
                >
                    <b-tag else :class="'is-' + props.row.role[0].alias">
                        {{ props.row.role[0].name }}
                    </b-tag>
                </b-table-column>

                <b-table-column
                    field="second_name"
                    label="Фамилия"
                    v-slot="props"
                    sortable
                >
                    {{ props.row.second_name }}
                </b-table-column>

                <b-table-column
                    field="first_name"
                    label="Имя"
                    v-slot="props"
                    sortable
                >
                    {{ props.row.first_name }}
                </b-table-column>

                <b-table-column
                    field="patronymic"
                    label="Отчество"
                    v-slot="props"
                    sortable
                >
                    {{ props.row.patronymic }}
                </b-table-column>

                <b-table-column
                    field="created_at"
                    label="Создан"
                    centered
                    v-slot="props"
                    sortable
                >
                    <span class="tag is-info">
                        {{ new Date(props.row.created_at).toLocaleDateString() }}
                    </span>
                </b-table-column>

                <b-table-column
                    label="Отдел"
                    v-slot="props"
                    sortable
                    field="department"
                >
                    <span>
                        {{ props.row.department }}
                    </span>
                </b-table-column>

                <b-table-column v-slot="props" label="Действия" v-if="!isWorker">
                    <b-button
                        v-if="canDeleteUser(props.row.role[0].alias)"
                        type="is-warning"
                        size="is-small"
                        icon-right="lock"
                        @click="changeUserBlockStatus(props.row.id, props.row.is_blocked)"
                    />
                    <b-button
                        type="is-danger"
                        size="is-small"
                        icon-right="trash"
                        v-if="canDeleteUser(props.row.role[0].alias)"
                        @click="onDeleteUser(props.row.id)"
                    />

<!--                    <b-tooltip label="Просмотреть пользователя"-->
<!--                               type="is-dark"-->
<!--                               position="is-top">-->

<!--                        <b-button type="is-info" size="is-small"-->
<!--                                  :disable="true"-->
<!--                                  icon-right="eye" @click="goToUser(props.row.id)"-->
<!--                        />-->
<!--                    </b-tooltip>-->
                </b-table-column>

                <b-table-column
                    v-slot="props"
                    label="Статус"
                    sortable
                    field="is_blocked"
                >
                    <b-tag type="is-danger" v-if="props.row.is_blocked" class="cursor-pointer">
                        <b-icon icon="times-circle" class="mr-1"></b-icon>Blocked
                    </b-tag>
                    <b-tag type="is-success" v-else class="cursor-pointer">
                        <b-icon icon="check-circle" class="mr-1"></b-icon>Active
                    </b-tag>
                </b-table-column>

                <template #footer>
                    <div class="has-text-right">
                        Пользователей: {{ users.length }}
                    </div>
                </template>
            </b-table>
            <DeleteUserModalConfirmation
                :visible="modalVisible"
                :id="userId"
                @close="modalVisible = false"
                @delete-user="deleteUser"
            />
        </template>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import DeleteUserModalConfirmation from "../modal/DeleteUserModalConfirmation";
import EventBus from "../../events/eventBus";
import userService from "../../services/user/userService";
import roleMixin from "../../mixins/roleMixin";
export default {
    name: "UsersList",
    components: {
        DeleteUserModalConfirmation,
        DefaultLayout
    },
    mixins: [roleMixin],
    data:() => ({
        modalVisible: false,
        userId: '',
        users: ''
    }),
    async mounted() {
        await this.getUsers();
        console.log(this.users);
    },
    methods: {
        async getUsers() {
            this.users = await userService.getUsers();
        },
        goToUser(id) {
            window.location.href = '/users/' + id;
        },
        onDeleteUser(id) {
            this.userId = id;
            this.modalVisible = true;
        },
        async deleteUser(id) {
            try {
                await userService.deleteUser(id);
                this.userId = '';
                this.modalVisible = false;
                EventBus.$emit('user-deleted');
                await this.getUsers();
            } catch (error) {
                this.userId = '';
                this.modalVisible = false;
                EventBus.$emit('error', error.message);
            }
        },
        async changeUserBlockStatus(id, status) {
            try {
                await userService.changeUserBlockStatus(id);
                this.userId = '';
                this.modalVisible = false;
                if (status) EventBus.$emit('user-unblocked');
                else EventBus.$emit('user-blocked');
                await this.getUsers();
            } catch (error) {
                this.userId = '';
                this.modalVisible = false;
                EventBus.$emit('error', error.message);
            }
        }
    },
}
</script>

<style scoped lang="scss">
.is {
    &-developer {
        background-color: green;
        color: #fff;
    }
    &-manager {
        background-color: orange;
        color: #fff;
    }
    &-administrator {
        background-color: blue;
        color: #fff;
    }
}
</style>
