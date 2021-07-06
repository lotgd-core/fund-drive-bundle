# LoTGD Bunde Fund Drive

Show a Fund Drive Goal in your LoTGD server. Use `sonata_block_render_event('lotgd_core.paypal')` for render Goal.

# Install
```bash
composer require lotgd-core/bundle-fund-drive
```

# Default config
```yaml
lotgd_fund_drive:
    # Monthly cost of LoTGD Server. This adds up to the final goal.
    expenses: 0
    # Goal amount of profit
    profit: 50
    # Deduct fees of PayPal from Goal
    deduct_fees: true
    # Currency usage for PayPal USD, EUR ...
    paypal_currency: USD
```
