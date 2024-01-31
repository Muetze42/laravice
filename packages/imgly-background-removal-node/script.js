const {
  // removeBackground,
  segmentForeground,
  applySegmentationMask
} = require('@imgly/background-removal-node')
const fs = require('fs')

const sourceFile = process.argv[2]
const targetFile = process.argv[3]
const fileExt = process.argv[4] ? process.argv[4] : 'png'
const quality = process.argv[5] ? parseFloat(process.argv[5]) : 1

function base_path(path) {
  return './../../' + path
}

let format
switch(fileExt) {
  case 'jpeg':
  case 'jpg':
    format = 'image/jpeg';
    break;
  case 'webp':
    format = 'image/webp';
    break;
  default:
    format = 'image/png';
}

async function run() {
  const config = {
    debug: false,
    // publicPath:  ...
    output: {
      quality: quality,
      format: format
    }
  }
  // const blob = await removeBackground('/../../' . sourceFile, config);
  const mask = await segmentForeground(base_path(sourceFile), config)
  const blob = await applySegmentationMask(base_path(sourceFile), mask, config)

  const buffer = await blob.arrayBuffer()
  try {
    await fs.promises.writeFile(base_path(targetFile), Buffer.from(buffer));
    console.log('process was successful')
  } catch (error) {
    console.error(error)
  }
}

run()
