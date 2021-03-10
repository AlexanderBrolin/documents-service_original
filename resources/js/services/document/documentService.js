import axiosService from "../axiosService";

export default {
    async classifyDocuments(payload) {
        const response = await axiosService.post('/documents/classify/tasks', payload);
        return response?.data;
    },
    async recognizeTask(taskId) {
        const response = await axiosService.post('/documents/recognize/task/' + taskId);
        return response?.data;
    },
    async getRecognizeResponseByTaskKey(payload) {
        const response = await axiosService.post('/documents/recognize/taskkey', payload);
        return response?.data;
    },
    async saveIndividual(payload) {
        const response = await axiosService.post('/individuals/create', payload);
        return response?.data;
    },
    async replaceDocument(payload) {
        return await axiosService.post('/documents/replace', payload);
    },
    async saveDocumentForIndividual(payload) {
        return await axiosService.post('/documents/individuals/add', payload);
    },
    async updateField(payload) {
        return await axiosService.put('/fields/update', payload);
    },
    async deleteDocument(docId) {
        return await axiosService.delete('/documents/' + docId);
    },
    async restoreDocument(payload) {
        return await axiosService.put('/documents/restore', payload);
    }
}
