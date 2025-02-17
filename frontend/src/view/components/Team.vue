<template>
  <div :class="classes">
    <div v-if="canViewBets" class="zone-match-bets">
      <div class="zone-match-bets-percentage">
        <a v-if="canBet" @click="doBet" class="zone-match-bets-bet-button"></a>
        <div class="zone-match-bets-percentage-number">{{ perc }}%</div>
        <div class="zone-match-bets-percentage-bar" :style="`height: ${perc}%`"></div>
      </div>
      <div class="zone-match-bets-overlay">
        <div class="zone-match-bets-title" v-t="bets.length > 0 ? 'NCZONE_MATCH_HAVE_BET' : 'NCZONE_MATCH_NO_BETS'"></div>
        <ul class="zone-match-betters">
          <li v-for="(bet, idx) in bets" :key="idx"><span v-html="bet.user.username"></span> ({{ renderBetTime(bet.timestamp) }})</li>
        </ul>
      </div>
    </div>

    <div class="zone-match-team">
      <div v-if="canReplacePlayer" class="zone-match-team-header zone-match-player-replace"></div>
      <div class="zone-match-team-header zone-match-player-name zone-highlight-color" v-t="title"></div>
      <div class="zone-match-team-header zone-match-player-rating">({{ totalRating }})</div>
      <div v-if="havePlayerCivs" class="zone-match-team-header zone-match-player-civ" v-t="'NCZONE_MATCH_CIVS'"></div>

      <template v-for="(player, idx) in players">
        <div v-if="canReplacePlayer" class="zone-match-player-replace" :key="`replace-${idx}`">
          <button class="zone-mini-button fa fa-times" @click="playerReplace(player.id)"></button>
        </div>
        <div class="zone-match-player-name zone-highlight-color" :key="`name-${idx}`"><span v-html="player.username"></span><span class="zone-match-player-rating-change" v-if="match.winner">({{ player.rating_change }})</span></div>
        <div class="zone-match-player-rating" :key="`rating-${idx}`">({{ player.rating }})</div>
        <div v-if="havePlayerCivs" class="zone-match-player-civ" :key="`civ${idx}`">
          <span class="zone-match-civ" v-if="player.civ">
            <span v-t="player.civ.title"></span>
            <span class="zone-match-civ-tooltip">{{ player.civ.multiplier }}</span>
          </span>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { pad } from '@/functions'

export default {
  name: 'nczone-team',
  props: {
    match: {
      type: Object,
      required: true
    },
    team: {
      type: Number,
      required: true
    }
  },
  methods: {
    doBet () {
      this.bet({ matchId: this.match.id, team: this.team })
    },
    renderBetTime (timestamp) {
      const secondsSinceDraw = timestamp - this.match.draw_time
      const seconds = secondsSinceDraw % 60
      const minutes = Math.floor(secondsSinceDraw / 60) % 60
      const hours = Math.floor(secondsSinceDraw / 3600)
      return (hours > 0 ? (hours + ':') : '') + pad(minutes) + ':' + pad(seconds)
    },
    playerReplace (userId) {
      this.openPlayerReplacePreviewOverlay(userId)
    },
    ...mapActions([
      'bet',
      'openPlayerReplacePreviewOverlay'
    ])
  },
  computed: {
    classes () {
      if (!this.isFinished) {
        return [`zone-match-team-${this.team}`]
      }

      if (this.isWinnerTeam) {
        return [`zone-match-team-${this.team}`, 'zone-match-team-winner']
      }

      if (this.isLoserTeam) {
        return [`zone-match-team-${this.team}`, 'zone-match-team-loser']
      }

      return [`zone-match-team-${this.team}`, 'zone-match-team-no-result']
    },
    isFinished () {
      return this.match.post_time > 0
    },
    isWinnerTeam () {
      return this.match.winner && this.team === this.match.winner
    },
    isLoserTeam () {
      return this.match.winner && this.team !== this.match.winner
    },
    canBet () {
      return this.canViewBets && !this.isFinished &&
        !this.match.bets.team1.map(p => p.user.id).includes(this.me.id) &&
        !this.match.bets.team2.map(p => p.user.id).includes(this.me.id)
    },
    canReplacePlayer () {
      return (this.canReplaceMod || (this.canReplaceUser && (this.match.drawer.id === this.me.id))) && !this.isFinished
    },
    perc () {
      if (!this.canViewBets) {
        return 0
      }

      const betCount = this.match.bets.team1.length + this.match.bets.team2.length
      const perc1 = betCount === 0 ? 50 : Math.round(this.match.bets.team1.length * 100 / betCount)
      const perc2 = 100 - perc1
      return this.team === 1 ? perc1 : perc2
    },
    players () {
      return this.team === 1 ? this.match.players.team1 : this.match.players.team2
    },
    bets () {
      if (!this.canViewBets) {
        return []
      }

      return this.team === 1 ? this.match.bets.team1 : this.match.bets.team2
    },
    title () {
      return this.team === 1 ? 'NCZONE_MATCH_TEAM1' : 'NCZONE_MATCH_TEAM2'
    },
    havePlayerCivs () {
      return !!this.players.find(p => !!p.civ)
    },
    totalRating () {
      return this.players.reduce((total, player) => total + player.rating, 0)
    },
    ...mapGetters([
      'me',
      'canReplaceMod',
      'canReplaceUser',
      'canViewBets'
    ])
  }
}
</script>
