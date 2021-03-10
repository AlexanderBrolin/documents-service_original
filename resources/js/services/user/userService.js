import axiosService from "../axiosService";

export default {
    async addUser(userData) {
        return await axiosService.post('/users', userData);
    },
    async deleteUser(userId) {
        return await axiosService.delete('/users/' + userId);
    },
    async changeUserBlockStatus(userId) {
        return await axiosService.put('/users/block/' + userId);
    },
    async getUsers() {
        const response = await axiosService.get('/users/all');
        return response?.data;
    },
}
