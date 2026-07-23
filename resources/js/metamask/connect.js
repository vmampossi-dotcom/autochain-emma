// Simple MetaMask connector for Laravel frontend assets
(function(window){
  const MetamaskConnector = {
    isInstalled() { return typeof window.ethereum !== 'undefined'; },
    async connect() {
      if (!this.isInstalled()) throw new Error('MetaMask non installé');
      const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
      return accounts[0];
    },
    async getAccounts() {
      if (!this.isInstalled()) return [];
      return await window.ethereum.request({ method: 'eth_accounts' });
    },
    async getChainId() {
      if (!this.isInstalled()) return null;
      return await window.ethereum.request({ method: 'eth_chainId' });
    },
    onAccountChanged(handler) { if (!this.isInstalled()) return; window.ethereum.on('accountsChanged', handler); },
    onChainChanged(handler) { if (!this.isInstalled()) return; window.ethereum.on('chainChanged', handler); },
    async signMessage(message) {
      if (!this.isInstalled()) throw new Error('MetaMask non installé');
      const accounts = await this.getAccounts();
      const from = accounts[0];
      if (!from) throw new Error('Aucun compte connecté');
      return await window.ethereum.request({ method: 'personal_sign', params: [message, from] });
    }
  };
  window.MetamaskConnector = MetamaskConnector;
})(window);
