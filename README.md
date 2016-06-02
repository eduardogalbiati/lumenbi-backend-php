#LumenBI - Backend

### How it Works

  - LumenBI Extracts data from another database and convert into his own unormalized database
  - The Importer Script runs every day
  - After gather the information, the applications creates "CurvaABC" info and "Status" on his own DB
  
### Design Patterns
  - I decided to use DataMapper with Translators and Hydrators to convert the data.
  - ExternalDataMapper: Connect to third party system/database and fetch information
  - Translator: Translate info to a search improved array
  - Hydrator: Add/Convert some especifc data
  - InternalDataMapper: Insert into database

