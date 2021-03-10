<template>
    <div class="container mt-10">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Авторизация
                    </div>
                    <div class="card-body">
                        <div class="errors mb-3" v-if="Object.keys(errors).length">
                            <div v-for="errorType in errors">
                                <b-message
                                    type="is-danger"
                                    v-for="(errorMessage, index) in errorType"
                                    :key="index"
                                    size="is-small"
                                >
                                    {{ errorMessage }}
                                </b-message>
                            </div>
                        </div>
                        <form @submit.prevent="login">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control"
                                           name="email"
                                           v-model="loginData.email"
                                           :class="{ 'is-invalid': emailErrors[0] }"
                                    >
                                    <span class="invalid-feedback" role="alert" v-if="emailErrors[0]">
                                        <span><b>{{ emailErrors[0] }}</b></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control"
                                           name="password"
                                           v-model="loginData.password"
                                           :class="{ 'is-invalid': passwordErrors[0] }"
                                    >
                                    <span class="invalid-feedback" role="alert" v-if="passwordErrors[0]">
                                        <span><b>{{ passwordErrors[0] }}</b></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="remember"
                                            id="remember"
                                            v-model="loginData.remember"
                                        >

                                        <label class="form-check-label" for="remember">
                                            Запомнить меня
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Войти
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { validationMixin } from 'vuelidate';
import { required, minLength, email } from 'vuelidate/lib/validators';
import * as actions from '../../store/modules/auth/types/actions';
import { mapActions } from 'vuex';
import EventBus from '../../events/eventBus';

export default {
    name: "Login",
    mixins: [validationMixin],
    validations: {
        loginData: {
            email: { required, email },
            password: { required, minLength: minLength(8) }
        }
    },
    data: () => ({
        loginData: {
            email: '',
            password: '',
            remember: false
        },
        errors: []
    }),
    methods: {
        ...mapActions('auth', {
            loginAction: actions.LOGIN
        }),
        async login() {
            this.$v.$touch();
            if (!this.$v.$invalid) {
                try {
                    await this.loginAction(this.loginData);
                    this.$v.$reset();
                    window.location.href = '/';
                } catch (error) {
                    if (!error.errors) {
                        EventBus.$emit('error', error.message);
                    } else {
                        this.errors = error.errors;
                    }
                }
            }
        }
    },
    computed: {
        emailErrors() {
            const errors = [];
            if (!this.$v.loginData['email'].$dirty) {
                return errors;
            }
            !this.$v.loginData['email'].email &&
                errors.push('Введите корректный E-mail');
            !this.$v.loginData['email'].required &&
                errors.push('E-mail обязательное поле');
            return errors;
        },
        passwordErrors() {
            const errors = [];
            if (!this.$v.loginData['password'].$dirty) {
                return errors;
            }
            !this.$v.loginData['password'].minLength &&
                errors.push('Пароль должен быть длинее 8 символов');
            !this.$v.loginData['password'].required &&
                errors.push('Пароль обязательное поле');
            return errors;
        },
    }
}
</script>

<style scoped>

</style>
