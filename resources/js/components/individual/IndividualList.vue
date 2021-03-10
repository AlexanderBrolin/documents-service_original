<template>
    <DefaultLayout>
        <template v-slot:title>
            Физические лица
        </template>
        <template v-slot:content>
            <b-loading :is-full-page="true" v-model="isLoading"></b-loading>
            <section v-if="!isLoading">
                <div class="search-block row">
                    <div class="col-md-4">
                        <b-field label="Поиск физ. лиц">
                            <dadata-suggestions
                                v-model="search.textString"
                                :fullInfo.sync="search.object"
                                field-value="unrestricted_value"
                                id="dadata-input"
                                class="input"
                            />
                        </b-field>
                    </div>
                    <div class="col-md-3">
                        <b-field label="СНИЛС">
                            <b-input
                                placeholder="626-029-036 22"
                                v-model="search.snilsNumber"
                            ></b-input>
                        </b-field>
                    </div>
                    <div class="col-md-3">
                        <b-field id="passport-label" label="Паспорт РФ / Серия Номер">
                            <b-input
                                placeholder="8914 935349"
                                v-model="search.passportNumber"
                            ></b-input>
                        </b-field>
                    </div>
                    <div class="col-md-2">
                        <b-field label="ИНН">
                            <b-input
                                placeholder="1234567890"
                                v-model="search.innNumber"
                            ></b-input>
                        </b-field>
                    </div>
                </div>
                <div class="d-flex justify-content-end my-3">
                    <div class="col-md-2 pr-0">
                        <b-button
                            expanded
                            type="is-danger"
                            icon-left="times"
                            @click="clearSearch"
                            v-if="!searchClear"
                            :loading="searchLoading"
                        >
                            Очистить
                        </b-button>
                    </div>
                    <div class="col-md-2 pr-0">
                        <b-button
                            expanded
                            type="is-primary"
                            icon-left="search"
                            @click="onSearch"
                            :loading="searchLoading"
                            :disabled="searchClear"
                            id="searchBtn"
                        >
                            Найти
                        </b-button>
                    </div>
                </div>

                <b-table
                    v-if="users.length"
                    :data="users"
                    :bordered="false"
                    :hoverable="true"
                    :mobile-cards="true"
                    sort-icon="arrow-up"
                    :sort-icon-size="'is-small'"
                    detailed
                    detail-key="id"
                    :show-detail-icon="true"
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
                        field="id"
                        label="ФИО"
                        v-slot="props"
                        centered
                    >
                        <a :href="`/individuals/${props.row.id}`">{{ getFullName(props.row) }}</a>
                    </b-table-column>

                    <b-table-column
                        field="created_at"
                        label="Дата создания"
                        v-slot="props"
                        sortable
                        centered
                    >
                            <span class="tag is-info">
                                {{ createdAt(props.row.created_at) }}
                            </span>
                    </b-table-column>

                    <b-table-column
                        label="Количество документов"
                        v-slot="props"
                        centered
                    >
                        {{ props.row.documents.length }}
                    </b-table-column>

                    <template #detail="props">
                        <article class="">
                            <h3 class="subtitle"><b>Документы</b></h3>
                            <ul>
                                <li
                                    v-for="document in props.row.documents"
                                    @key="document.id"
                                >
                                    {{ getDocumentNameByKey(document.type) }}
                                </li>
                            </ul>
                        </article>
                    </template>

                    <template #footer>
                        <div class="has-text-right">
                            Физических лиц: {{ users.length }}
                        </div>
                    </template>
                </b-table>
                <b-message type="is-danger" v-if="isWorker && !users.length">
                    Вы не можете просмотреть список существующих физических лиц.
                    Вы можете использовать поиск, чтобы найти нужное Вам лицо!
                </b-message>
                <b-message type="is-danger" v-else-if="!users.length">
                    Не найдено ни одного физического лица!
                </b-message>
            </section>
        </template>
    </DefaultLayout>
</template>

<script>
import roleMixin from "../../mixins/roleMixin";
import DefaultLayout from "../layouts/DefaultLayout";
import datetimeMixin from "../../mixins/datetimeMixin";
import individualsMixin from "../../mixins/individualsMixin";
import individualService from "../../services/individual/individualService";
import EventBus from "../../events/eventBus";

export default {
    name: "PhysicalList",
    mixins: [roleMixin, datetimeMixin, individualsMixin],
    components: {
        DefaultLayout
    },
    data: () => ({
       users: [],
        isLoading: true,
        showDetailIcon: true,
        searchValue: '',
        searchObject: {},
        search: {
            textString: '',
            object: {},
            snilsNumber: '',
            passportNumber: '',
            innNumber: ''
        },
        searchLoading: false,
        searchResult: {},
        searchClear: true
    }),
    async mounted() {

        console.log(this.$refs.searchBtn)
        if (!this.isWorker) {
            await this.loadIndividuals();
        }
        setTimeout(() => {
            document.getElementById('dadata-input').placeholder = 'ФИО';
            document.querySelector('div.suggestions-suggestions').style.position = 'fixed';
        }, 1);
        const self = this;
        document.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                self.onSearch();
            }
        });

        this.isLoading = false;
    },
    watch: {
        search: {
            handler() {
                this.searchResult = {
                    name: this.search.object?.data?.name?.toLowerCase(),
                    surname: this.search.object?.data?.surname?.toLowerCase(),
                    patronymic: this.search.object?.data?.patronymic?.toLowerCase(),
                    snils: this.search.snilsNumber,
                    inn: this.search.innNumber,
                    passport: this.search.passportNumber
                };
                this.searchClear = !Object.values(this.searchResult).filter(item => item).length;
            },
            deep: true
        }
    },
    methods: {
        async loadIndividuals() {
            try {
                this.isLoading = true;
                this.users = await individualService.getIndividualUsers();
                this.isLoading = false;
                setTimeout(() => {
                    document.getElementById('dadata-input').placeholder = 'ФИО';
                    document.querySelector('div.suggestions-suggestions').style.position = 'fixed';
                }, 1);
            } catch (error) {
                EventBus.$emit('error', error.message);
            }
            setTimeout(() => {
                document.getElementById('dadata-input').placeholder = 'ФИО';
                document.querySelector('div.suggestions-suggestions').style.position = 'fixed';
            }, 1);
        },
        async clearSearch() {
            Object.keys(this.search).map(key => {
                this.search[key] = '';
            });
            if (!this.isWorker) {
                await this.loadIndividuals();
            } else {
                this.users = [];
            }
            setTimeout(() => {
                document.getElementById('dadata-input').placeholder = 'ФИО';
                document.querySelector('div.suggestions-suggestions').style.position = 'fixed';
            }, 1);
        },
        async onSearch() {
            if (!this.searchClear) {
                try {
                    this.isLoading = true;
                    this.searchLoading = true;
                    this.users = await individualService.search(this.searchResult);
                    this.searchLoading = false;
                    this.isLoading = false;
                } catch (error) {
                    this.searchLoading = false;
                    EventBus.$emit('error', error.message);
                }
            }
        }
    }
}
</script>

<style scoped>
ul {
    list-style: disc;
}
.dadata-input {
    padding: 7px 15px;
}
</style>
