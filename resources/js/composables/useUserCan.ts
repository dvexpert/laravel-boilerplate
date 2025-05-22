import { SharedData } from '@/types'
import { usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

export function useUserCan() {
    const page = usePage<SharedData>()
    const userCan = ref<SharedData['auth']['can']>(page.props.auth.can)

    watch(
        () => page.props.auth.can,
        (newCan) => {
            userCan.value = newCan
        },
    )

    return {
        userCan,
        can: (permission: keyof SharedData['auth']['can'] | boolean) => {
            if (typeof permission !== 'string') {
                return true
            }

            return !!userCan.value[permission]
        },
    }
}
