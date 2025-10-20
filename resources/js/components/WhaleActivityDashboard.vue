<template>
  <!-- Mobile overlay -->
  <div class="overlay" :class="{ 'active': sidebarOpen }" @click="sidebarOpen = false"></div>
  
  <div class="flex h-screen" :class="{ 'dark': darkMode }">
    <!-- Sidebar -->
    <div class="sidebar bg-white dark:bg-gray-800 w-64 shadow-md" :class="{ 'active': sidebarOpen }">
      <div class="p-6 border-b dark:border-gray-700">
        <h1 class="text-xl font-bold text-indigo-600 dark:text-indigo-400 flex items-center">
          <i class="fas fa-chart-line mr-2"></i> WhaleTracker
        </h1>
      </div>
      
      <nav class="mt-6">
        <div class="px-4 mb-4">
          <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wider">Main</p>
        </div>
        
        <a v-for="item in menuItems" :key="item.id" 
           href="#" 
           class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
           :class="{ 'bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-600 dark:border-indigo-400': item.active }"
           @click.prevent="setActiveItem(item.id)">
          <i :class="item.icon" class="mr-3"></i>
          <span>{{ item.name }}</span>
        </a>
      </nav>
      
      <div class="absolute bottom-0 w-full p-4 border-t dark:border-gray-700">
        <div class="flex items-center">
          <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
            <i class="fas fa-user text-indigo-600 dark:text-indigo-400"></i>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Whale Trader</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Premium</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Bar -->
      <header class="bg-white dark:bg-gray-800 shadow-sm z-10">
        <div class="flex items-center justify-between px-6 py-4">
          <div class="flex items-center">
            <button @click="toggleSidebar" class="text-gray-500 dark:text-gray-400 focus:outline-none lg:hidden">
              <i class="fas fa-bars text-xl"></i>
            </button>
            <div class="relative mx-4 lg:mx-0">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
              </span>
              <input 
                class="w-32 pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-500 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 lg:w-64" 
                type="text" 
                placeholder="Search token..." 
                v-model="search"
              >
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <!-- Dark Mode Toggle -->
            <button 
              @click="toggleDarkMode" 
              class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
              :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
            >
              <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
            </button>

            <select v-model="perPage" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
              <option :value="10">10 rows</option>
              <option :value="25">25 rows</option>
              <option :value="50">50 rows</option>
              <option :value="100">100 rows</option>
              <option :value="9999">Show All</option>
            </select>
            
            <button class="flex mx-4 text-gray-600 dark:text-gray-400 focus:outline-none">
              <i class="fas fa-bell text-xl"></i>
            </button>
          </div>
        </div>
      </header>
      
      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">🧠 Solana Whale Activity Tracker</h2>
          <p class="text-gray-600 dark:text-gray-400">Monitor large cryptocurrency transactions and whale movements in real-time.</p>
        </div>
        
        <!-- Type Toggle -->
        <div class="type-toggle flex space-x-2 mb-6">
          <button 
            @click="filterType = 'ALL'" 
            class="px-4 py-2 rounded-lg transition-colors"
            :class="filterType === 'ALL' ? 'bg-indigo-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'"
          >
            All
          </button>
          <button 
            @click="filterType = 'TRANSFER'" 
            class="px-4 py-2 rounded-lg transition-colors"
            :class="filterType === 'TRANSFER' ? 'bg-indigo-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'"
          >
            Transfer 🔁
          </button>
          <button 
            @click="filterType = 'BURN'" 
            class="px-4 py-2 rounded-lg transition-colors"
            :class="filterType === 'BURN' ? 'bg-indigo-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'"
          >
            Burn 🔥
          </button>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 mr-4">
                <i class="fas fa-exchange-alt text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transactions</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ filteredTokens.length }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 mr-4">
                <i class="fab fa-bitcoin text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Value</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">${{ formatAmount(totalValue) }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mr-4">
                <i class="fas fa-wallet text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Unique Tokens</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ uniqueTokens }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400 mr-4">
                <i class="fas fa-users text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Whale Wallets</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ uniqueWallets }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Section - Full Width at Top -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Token Value Chart -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Token Value Distribution</h3>
              <div class="flex space-x-2">
                <button class="text-xs px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full">24H</button>
                <button class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">7D</button>
              </div>
            </div>
            <div class="h-80">
              <TokenValueChart :tokens="filteredTokens" :dark-mode="darkMode" />
            </div>
          </div>
          
          <!-- Whale Time Series Chart -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Transaction Timeline</h3>
              <div class="flex space-x-2">
                <button class="text-xs px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full">24H</button>
                <button class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">7D</button>
              </div>
            </div>
            <div class="h-80">
              <WhaleTimeSeriesChart :tokens="filteredTokens" :dark-mode="darkMode" />
            </div>
          </div>
        </div>
        
        <!-- Transactions Table - Full Width Below Charts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Recent Whale Transactions</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ sorted.length }} total transactions</span>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" @click="sortBy('token_name')">
                    Token
                    <i class="fas fa-sort ml-1" v-if="sortKey !== 'token_name'"></i>
                    <i class="fas fa-sort-up ml-1" v-if="sortKey === 'token_name' && sortAsc"></i>
                    <i class="fas fa-sort-down ml-1" v-if="sortKey === 'token_name' && !sortAsc"></i>
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" @click="sortBy('amount')">
                    Amount
                    <i class="fas fa-sort ml-1" v-if="sortKey !== 'amount'"></i>
                    <i class="fas fa-sort-up ml-1" v-if="sortKey === 'amount' && sortAsc"></i>
                    <i class="fas fa-sort-down ml-1" v-if="sortKey === 'amount' && !sortAsc"></i>
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">From</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">To</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" @click="sortBy('value_usd')">
                    Value (USD)
                    <i class="fas fa-sort ml-1" v-if="sortKey !== 'value_usd'"></i>
                    <i class="fas fa-sort-up ml-1" v-if="sortKey === 'value_usd' && sortAsc"></i>
                    <i class="fas fa-sort-down ml-1" v-if="sortKey === 'value_usd' && !sortAsc"></i>
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" @click="sortBy('detected_at')">
                    Time
                    <i class="fas fa-sort ml-1" v-if="sortKey !== 'detected_at'"></i>
                    <i class="fas fa-sort-up ml-1" v-if="sortKey === 'detected_at' && sortAsc"></i>
                    <i class="fas fa-sort-down ml-1" v-if="sortKey === 'detected_at' && !sortAsc"></i>
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TX</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="(tx, index) in paginated" :key="tx.tx_id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ (page - 1) * perPage + index + 1 }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mr-3">
                        <i class="fab fa-bitcoin text-indigo-600 dark:text-indigo-400 text-xs"></i>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ tx.token_name || 'Unknown' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ tx.type || 'TRANSFER' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">{{ formatAmount(tx.amount) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ tx.from_entity || 'Unknown' }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-32">{{ shortenAddress(tx.wallet) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ tx.to_entity || 'Unknown' }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-32">{{ shortenAddress(tx.to_wallet) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${{ formatAmount(tx.value_usd) }}</div>
                    <div class="text-xs text-green-600 dark:text-green-400 font-medium" v-if="tx.value_usd > 1000000">
                      <i class="fas fa-arrow-up mr-1"></i>Large
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ formatTime(tx.detected_at) }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(tx.detected_at) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a :href="tx.solscan_url" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="View on Solscan">
                      <i class="fas fa-external-link-alt"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing <span class="font-medium">{{ (page - 1) * perPage + 1 }}</span> to 
                <span class="font-medium">{{ Math.min(page * perPage, sorted.length) }}</span> of 
                <span class="font-medium">{{ sorted.length }}</span> results
              </div>
              <div class="flex space-x-2">
                <button 
                  @click="prevPage" 
                  :disabled="page === 1"
                  class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Previous
                </button>
                <button 
                  @click="nextPage" 
                  :disabled="page >= totalPages"
                  class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import TokenValueChart from './TokenValueChart.vue';
import WhaleTimeSeriesChart from './WhaleTimeSeriesChart.vue';

const walletLabels = {
  'Binance 2': 'Binance',
  'Binance 10': 'Binance',
  'Circle Treasury': 'Circle',
  'Coinbase 1': 'Coinbase',
  'Crypto.com Hot Wallet 1': 'Crypto.com'
};

export default {
  name: 'WhaleActivityDashboard',
  components: {
    TokenValueChart,
    WhaleTimeSeriesChart
  },
  data() {
    return {
      sidebarOpen: false,
      darkMode: false,
      tokens: [],
      filterType: 'ALL',
      search: '',
      page: 1,
      perPage: 10,
      sortKey: 'detected_at',
      sortAsc: false,
      menuItems: [
        { id: 1, name: 'Dashboard', icon: 'fas fa-home', active: true },
        { id: 2, name: 'Whale Tracker', icon: 'fas fa-search-dollar', active: false },
        { id: 3, name: 'Transactions', icon: 'fas fa-exchange-alt', active: false },
        { id: 4, name: 'Portfolios', icon: 'fas fa-wallet', active: false },
        { id: 5, name: 'Alerts', icon: 'fas fa-bell', active: false },
        { id: 6, name: 'Analytics', icon: 'fas fa-chart-bar', active: false },
        { id: 7, name: 'Settings', icon: 'fas fa-cog', active: false }
      ]
    };
  },
  computed: {
    filteredTokens() {
      let filtered = this.tokens.filter(tx =>
        tx.token_name?.toLowerCase().includes(this.search.toLowerCase())
      );
      if (this.filterType !== 'ALL') {
        filtered = filtered.filter(tx => tx.type === this.filterType);
      }
      return filtered;
    },
    sorted() {
      if (!this.sortKey) return this.filteredTokens;
      return [...this.filteredTokens].sort((a, b) => {
        let valA = a[this.sortKey];
        let valB = b[this.sortKey];
        
        // Handle date sorting
        if (this.sortKey === 'detected_at') {
          valA = valA instanceof Date ? valA.getTime() : 0;
          valB = valB instanceof Date ? valB.getTime() : 0;
          return this.sortAsc ? (valA - valB) : (valB - valA);
        }
        
        // Handle numeric sorting
        if (typeof valA === 'number' && typeof valB === 'number') {
          return this.sortAsc ? (valA - valB) : (valB - valA);
        }
        
        // Handle string sorting
        valA = String(valA || '').toLowerCase();
        valB = String(valB || '').toLowerCase();
        return this.sortAsc ? (valA > valB ? 1 : -1) : (valA < valB ? 1 : -1);
      });
    },
    paginated() {
      const start = (this.page - 1) * this.perPage;
      const end = start + this.perPage;
      return this.sorted.slice(start, end);
    },
    totalPages() {
      return Math.max(1, Math.ceil(this.sorted.length / this.perPage));
    },
    totalValue() {
      return this.filteredTokens.reduce((sum, tx) => sum + (tx.value_usd || 0), 0);
    },
    uniqueTokens() {
      return new Set(this.filteredTokens.map(tx => tx.token_name)).size;
    },
    uniqueWallets() {
      const wallets = new Set();
      this.filteredTokens.forEach(tx => {
        if (tx.wallet) wallets.add(tx.wallet);
        if (tx.to_wallet) wallets.add(tx.to_wallet);
      });
      return wallets.size;
    }
  },
  watch: {
    perPage() {
      // Reset to first page when changing items per page
      this.page = 1;
    },
    search() {
      // Reset to first page when searching
      this.page = 1;
    },
    filterType() {
      // Reset to first page when changing filter
      this.page = 1;
    }
  },
  mounted() {
    // Check for saved theme preference or use system preference
    const savedTheme = localStorage.getItem('whale-dashboard-darkMode');
    if (savedTheme !== null) {
      this.darkMode = JSON.parse(savedTheme);
    } else {
      // Check system preference
      this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    
    // Apply dark mode to document
    this.applyDarkMode();
    
    this.fetchData();
    setInterval(this.fetchData, 30000); // refresh every 30s
  },
  methods: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen;
    },
    toggleDarkMode() {
      this.darkMode = !this.darkMode;
      localStorage.setItem('whale-dashboard-darkMode', JSON.stringify(this.darkMode));
      this.applyDarkMode();
    },
    applyDarkMode() {
      if (this.darkMode) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    },
    setActiveItem(itemId) {
      this.menuItems.forEach(item => {
        item.active = item.id === itemId;
      });
    },
    fetchData() {
      axios.get('/api/whale-activities', { responseType: 'text' })
        .then(res => {
          try {
            const jsonStart = res.data.indexOf('[');
            const jsonString = res.data.slice(jsonStart);
            const parsed = JSON.parse(jsonString);
            this.tokens = parsed.map(tx => ({
              ...tx,
              amount: parseFloat(tx.amount) || 0,
              value_usd: parseFloat(tx.value_usd) || 0,
              detected_at: tx.detected_at ? new Date(tx.detected_at) : new Date(),
              type: tx.type || 'TRANSFER',
              from_entity: walletLabels[tx.wallet] || (tx.wallet ? 'Unknown Entity' : '—'),
              to_entity: walletLabels[tx.to_wallet] || (tx.to_wallet ? 'Unknown Entity' : '—')
            }));
          } catch (error) {
            console.error('Error parsing whale activities:', error);
            // Fallback to sample data if API fails
            this.tokens = this.getSampleData();
          }
        })
        .catch(err => {
          console.error('Failed to fetch whale activity:', err);
          // Fallback to sample data if API fails
          this.tokens = this.getSampleData();
        });
    },
    getSampleData() {
      // Sample data for testing pagination
      return Array.from({ length: 25 }, (_, i) => ({
        tx_id: `sample-${i + 1}`,
        token_name: `SOL${i % 5 === 0 ? '' : i % 3}`,
        amount: (i + 1) * 1000,
        value_usd: (i + 1) * 50000,
        detected_at: new Date(Date.now() - i * 60000),
        type: i % 3 === 0 ? 'TRANSFER' : 'BURN',
        wallet: `Wallet${i}`,
        to_wallet: `ToWallet${i}`,
        from_entity: i % 4 === 0 ? 'Binance' : 'Unknown',
        to_entity: i % 4 === 1 ? 'Coinbase' : 'Unknown',
        solscan_url: '#'
      }));
    },
    formatAmount(val) {
      return typeof val === 'number' && !isNaN(val)
        ? val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
        : '—';
    },
    formatTime(dateObj) {
      return dateObj instanceof Date && !isNaN(dateObj.getTime())
        ? dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        : '—';
    },
    formatDate(dateObj) {
      return dateObj instanceof Date && !isNaN(dateObj.getTime())
        ? dateObj.toLocaleDateString([], { month: 'short', day: 'numeric' })
        : '—';
    },
    shortenAddress(address) {
      if (!address || address === '—') return '—';
      return address.length > 10 
        ? `${address.substring(0, 6)}...${address.substring(address.length - 4)}`
        : address;
    },
    sortBy(key) {
      if (this.sortKey === key) {
        this.sortAsc = !this.sortAsc;
      } else {
        this.sortKey = key;
        this.sortAsc = true;
      }
      // Reset to first page when sorting
      this.page = 1;
    },
    nextPage() {
      console.log('Next page clicked', this.page, this.totalPages);
      if (this.page < this.totalPages) {
        this.page++;
        console.log('Page changed to:', this.page);
      }
    },
    prevPage() {
      console.log('Prev page clicked', this.page);
      if (this.page > 1) {
        this.page--;
        console.log('Page changed to:', this.page);
      }
    }
  }
};
</script>

<style scoped>
.type-toggle button.active {
  background: #4f46e5;
  color: white;
  font-weight: bold;
}

.chart-container {
  height: 300px;
}

/* Ensure dark mode transitions work smoothly */
* {
  transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}
</style>