const fs = require('fs');

function ensureDirectoriesExist(directories) {
  for (const dir of directories) {
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
  }
}

module.exports = { ensureDirectoriesExist }; 