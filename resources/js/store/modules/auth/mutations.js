import * as mutations from './types/mutations';

export default {
    [mutations.SET_USER_DATA]: (state, userData) => {
        state.user = userData;
    },
    [mutations.LOG_IN]: state => {
        state.isLoggedIn = true;
    },
    [mutations.LOG_OUT]: state => {
        state.isLoggedIn = false;
        state.user = null;
    }
}
