const fs = require('fs');
const path = require('path');

const appBuild = path.resolve(__dirname, 'build');

function renameFilesInDir(dir) {
  const files = fs.readdirSync(dir);

  files.forEach((file) => {
    const filePath = path.resolve(dir, file);
    const fileStat = fs.statSync(filePath);

    if (fileStat.isDirectory()) {
      renameFilesInDir(filePath);
    } else {
      if (file.startsWith('main.') && (file.endsWith('.js') || file.endsWith('.css'))) {
        const newFileName = file.replace(/\..+\./, '.');
        fs.renameSync(filePath, path.resolve(dir, newFileName));
      }
    }
  });
}

renameFilesInDir(appBuild);
