import * as getters from './types/getters';

export default {
    [getters.GET_USER_DATA]: state => state.user,
    [getters.IS_LOGGED_IN]: state => state.isLoggedIn,
};
