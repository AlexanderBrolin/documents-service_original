import moment from "moment";

export default {
    methods: {
        createdAt(time) {
            return moment(time).format('DD-MM-yyyy, HH:mm:ss');
        },
    }
}
