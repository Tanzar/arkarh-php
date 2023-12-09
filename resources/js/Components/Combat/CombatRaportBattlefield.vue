<script setup>
import CombatRaportSide from './CombatRaportSide.vue';


let props = defineProps({
    state: {
        type: Object,
        default: () => { 
            return {
                attackers: {
                    front: {},
                    back: {},
                    graveyard: {},
                    reserves: {}
                },
                defenders: {
                    front: {},
                    back: {},
                    graveyard: {},
                    reserves: {}
                }
            };
        }
    }
});

</script>

<template>
    <div class="battlefield">
        <CombatRaportSide :state="state.attackers" />
        <CombatRaportSide :state="state.defenders" reversed />
    </div>
</template>

<style lang="scss" scoped>

</style>