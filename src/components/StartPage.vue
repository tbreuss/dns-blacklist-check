<template>
  <div class="start-page">
    <h1 class="cover-heading">Are your Blacklisted?</h1>
    <p class="lead">We will test your server domain or IP address against over 57 DNS based email blacklists.</p>
    <form v-on:submit.prevent="run">
      <div class="input-group input-group-lg">
        <input v-model="msg" type="text" class="form-control" placeholder="Server domain or IP address" aria-label="Recipient's username" aria-describedby="basic-addon2" autofocus>
        <div class="input-group-append">
          <button v-on:click="run" type="button" class="btn btn-danger">{{ buttonLabel }}</button>
        </div>
      </div>
    </form>
    <div v-if="totalItems>-1">
      <p class="grey">{{ items.length }} of {{ totalItems }} dnsbl-server</p>
      <table>
        <tr>
          <th style="width:55%">Host</th>
          <th style="width:35%">Response Time</th>
          <th style="width:10%"></th>
        </tr>
        <tr v-for="item in items" :key="item.cnt" v-bind:item="item">
          <td>{{ item.host }}</td>
          <td>{{ item.time }}</td>
          <td v-if="item.listed" class="text-center bl-yes">Yes</td>
          <td v-else class="text-center bl-no">No</td>
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
      msg: '',
      actualMsg: '',
      totalItems: -1,
      items: [],
      loading: false
    }
  },
  computed: {
    buttonLabel: function () {
      return (this.loading ? 'Testing...' : 'Test')
    }
  },
  methods: {
    run: function () {
      this.reset()

      const streamUrl = process.env.VUE_APP_API_URL + '?ip=' + encodeURIComponent(this.msg)
      const evtSource = new EventSource(streamUrl)
      this.loading = true

      evtSource.addEventListener('header', (e) => {
        const header = JSON.parse(e.data)
        this.totalItems = header.totalItems
        this.actualMsg = header.msg
      }, false)

      evtSource.addEventListener('item', (e) => {
        const item = JSON.parse(e.data)
        this.items.push(item)
      }, false)

      evtSource.addEventListener('close', () => {
        evtSource.close()
        this.loading = false
      }, false)
    },
    reset: function () {
      if (evtSource !== false) {
        evtSource.close()
      }
      this.loading = false
      this.items = []
      this.totalItems = -1
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
  background-color:#444;
}
td {
  background-color:#444;
  border-top: 2px solid #333;
  text-align: left;
}
.modal-content {
  background-color:#333;
  height:100%;
}
.modal-dialog {
  max-width: 100%;
  height: 100%;
  margin: 0;
}
.bl-yes {
  background-color:lightcoral;
}
.bl-no {
  background-color:lightseagreen;
}
.grey {
  color:rgba(255,255,255,0.5);
  margin:1rem 0;
}
</style>
