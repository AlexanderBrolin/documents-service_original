import * as actionTypes from './types/actions';
import * as mutationTypes from './types/mutations';
import authService from "../../../services/auth/authService";

export default {
    [actionTypes.LOGIN]: async ({ commit }, payload) => {
        const response = await authService.login(payload);
        commit(mutationTypes.SET_USER_DATA, response.data.user);
        commit(mutationTypes.LOG_IN);
    },
    [actionTypes.LOGOUT]: async ({ commit }) => {
        await authService.logout();
        commit(mutationTypes.LOG_OUT);
    }
}
