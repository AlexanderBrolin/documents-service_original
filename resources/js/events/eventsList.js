export default {
    'manager-added': {
        message: 'Руководитель успешно создан!',
        type: 'is-success',
        position: 'is-bottom',
        duration: 2500
    },
    'user-deleted': {
        message: 'Пользователь успешно удален!',
        type: 'is-success',
        position: 'is-bottom',
        duration: 2500
    },
    'user-blocked': {
        message: 'Пользователь успешно заблокирован!',
        type: 'is-warning',
        position: 'is-bottom',
        duration: 2500
    },
    'user-unblocked': {
        message: 'Пользователь успешно разблокирован!',
        type: 'is-warning',
        position: 'is-bottom',
        duration: 2500
    },
    error(message) {
        return {
            message,
            type: 'is-danger',
            position: 'is-top',
            duration: 2500
        }
    },
    success(message) {
        return {
            message,
            type: 'is-success',
            position: 'is-top',
            duration: 2500
        }
    },
    warning(message) {
        return {
            message,
            type: 'is-warning',
            position: 'is-top',
            duration: 2500
        }
    }
}
