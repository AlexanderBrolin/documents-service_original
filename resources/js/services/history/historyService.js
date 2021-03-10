import axiosService from "../axiosService";

export default {
    async getHistoryForIndividual(id) {
        const response = await axiosService.get('/field-history/individual/' + id);
        return response?.data;
    }
}
