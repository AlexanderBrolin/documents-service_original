import * as getterTypes from '../store/modules/auth/types/getters';
import { mapGetters } from 'vuex';

export default {
    data: () => ({
       roleValues: {
           worker: 1,
           manager: 2,
           administrator: 3,
           developer: 4
       }
    }),
    computed: {
        ...mapGetters('auth', {
            authUser: getterTypes.GET_USER_DATA
        }),
        developerOrAdministrator() {
            return this.authUser.role[0].alias === 'developer' || this.authUser.role[0].alias === 'administrator';
        },
        canAddNewDocumentType() {
            return this.isDeveloper;
        },
        isDeveloper() {
            return this.authUser.role[0].alias === 'developer';
        },
        isWorker() {
            return this.authUser.role[0].alias === 'worker';
        },
        adminOrMore() {
            return this.roleValues[this.authUser.role[0].alias] >= this.roleValues.administrator;
        }
    },
    methods: {
        canDeleteUser(userRole) {
            return this.roleValues[this.authUser.role[0].alias] > this.roleValues[userRole];
        }
    }
}
