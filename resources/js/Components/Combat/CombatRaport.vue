<script setup>
import CombatRaportBattlefield from './CombatRaportBattlefield.vue';
import { ref, watch } from 'vue';

let props = defineProps({
    states: {
        type: Object,
        required: true,
    }
});

let stage = 1;

let tickStage = 1;

let state = ref({});

let texts = ref([]);

watch(props, function(newValue) {
    stage = 1;
    tickStage = 1;
    parseLogs();
});

function parseLogs() {
    let newState = {
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
    }
    let newTexts = [];

    if (tickStage > stage) {
        tickStage = 1;
    }
    console.log('Tick: ' + tickStage + '; Stage: ' + stage);
    for (let i = tickStage; i <= stage; i++) {
        let stageLogs = props.states[i];
        let source = {side: '', line: '', position: ''};
        stageLogs.forEach((logLine) => {
            let log = JSON.parse(JSON.stringify(logLine));
            let target = (i !== tickStage && i === stage);
            if (i === stage) {
                newTexts.push(log.text);
            }
            switch(log.type) {
                case 'tick':
                    tickStage = i;
                    break;
                case 'state':
                    log.target = target;
                    log.source = (target && log.side === source.side && log.line === source.line && log.position === source.position);
                    newState[log.side][log.line][log.position] = log;
                    break;
                case 'ability':
                    source = {side: log.side, line: log.line, position: log.position};
                    newState[log.side][log.line][log.position]['source'] = target;
                    break;
            }
        });
    }
    state.value = newState;
    texts.value = newTexts;
}

function next() {
    if (stage < Object.keys(props.states).length) {
        stage++;
        parseLogs();
    }
}

function previous() {
    if (stage > 1) {
        stage--;
        parseLogs();
    }
}

</script>

<template>
    <div class="raport">
        <div class="raport-row">
            <CombatRaportBattlefield :state="state" />
            <div class="raport-tools">
                <button @click="previous">Previous</button>
                <button @click="next">Next</button>
            </div>
        </div>
        <div class="raport-row">
            <div class="raport-log">
                <template v-for="text in texts">
                    <div class="raport-log-text">{{ text }}</div>
                </template>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

.raport {
    display: flex;
    flex-direction: column;
    width: fit-content;
}

.raport-row {
    display: flex;
    flex-direction: row;
}

.raport-log {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 200px;
    border: 3px black solid;
}

.raport-log-text {
    border-bottom: 1px black solid;
}
</style>