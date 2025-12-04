import yfinance as yf
from datetime import datetime, timedelta

# 1. Define the tickers
tickers = ['INTC', 'BTC-USD']

# 2. Calculate start and end dates (5 years back from today)
end_date = datetime.now()
start_date = end_date - timedelta(days=5*365)

print(f"Downloading data from {start_date.date()} to {end_date.date()}...")

# 3. Download and save data
for ticker in tickers:
    # Fetch data
    data = yf.download(ticker, start=start_date, end=end_date)
    
    # Save to CSV
    filename = f"{ticker}_5y_history.csv"
    data.to_csv(filename)
    print(f"Saved {filename}")

print("Download complete!")