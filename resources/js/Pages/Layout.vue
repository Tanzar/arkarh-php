<script setup>
import axios from 'axios';

Echo.connector.pusher.connection.bind('connected', () => {
  console.log('connected');
});

Echo.channel('main').listenToAll((e, t) => {
  console.log(e);
  console.log(t);
})

function click() {
  axios.get(route('test')).then(result => {
    console.log(result.data);
  });
}

</script>

<template>
  <main>
    <header>
      Ja layout
    </header>
    <body>
      <button @click="click">Test</button>
      <slot />
    </body>
  </main>
</template>