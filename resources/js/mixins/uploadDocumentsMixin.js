export default {
    data: () => ({
        availableTypes: [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/pdf',
            'image/bmp',
            'image/tiff',
            'image/gif',
            'image/vnd.djvu',
            'image/djvu'
        ],
    }),
    methods: {
        getLevelOfConfidence(confidence) {
            if (confidence >= 0 && confidence < 0.49) return 'low';
            if (confidence >= 0.49 && confidence < 0.7) return 'middle';
            if (confidence > 0.7) return 'high';
        },
    },
    computed: {
        maxFileSize() {
            return 10000000; // 10 MB
        },
    }

}
