import axiosService from "../axiosService";

export default {
    async fetchTasks() {
        const response = await axiosService.get('/tasks/all');
        return response?.data;
    }
}
