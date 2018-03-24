<template>
  <div class="start-page">
    <form v-on:submit.prevent="run">
      <input v-model="msg" type="text" placeholder="Enter message"/>
      <button v-on:click="run" type="button">{{ buttonLabel }}</button>
    </form>
    <div v-if="total_items>-1">
      <p>{{ items.length }} of {{ total_items }} dnsbl-server</p>
      <table>
        <tr>
          <th>Host</th>
          <th>Response Time</th>
          <th>Listed</th>
        </tr>
        <tr v-for="item in items" :key="item.cnt" v-bind:item="item">
          <td>{{ item.host }}</td>
          <td>{{ item.time }}</td>
          <td>{{ item.listed }}</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>

let evtSource = false

export default {
  name: 'StartPage',
  data () {
    return {
      msg: '94.16.152.175',
      actual_msg: '',
      total_items: -1,
      items: [],
      loading: false
    }
  },
  computed: {
    buttonLabel: function () {
      return (this.loading ? 'Loading…' : 'Go')
    }
  },
  methods: {
    run: function () {
      this.reset()

      var streamUrl = 'http://localhost:9090/index.php' + '?ip=' + encodeURIComponent(this.msg)

      evtSource = new EventSource(streamUrl)
      this.loading = true

      var that = this

      evtSource.addEventListener('header', function (e) {
        var header = JSON.parse(e.data)
        that.total_items = header.total_items
        that.actual_msg = header.msg
      }, false)

      evtSource.addEventListener('item', function (e) {
        var item = JSON.parse(e.data)
        that.items.push(item)
      }, false)

      evtSource.addEventListener('close', function (e) {
        evtSource.close()
        that.loading = false
      }, false)
    },
    reset: function () {
      if (evtSource !== false) {
        evtSource.close()
      }
      this.loading = false
      this.items = []
      this.total_items = -1
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
a {
  color: #42b983;
}
table {
  width:100%;
}
th {
  text-align:left;
}
</style>
