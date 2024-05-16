// Sử dụng function để đóng gói logic và giảm lặp code

console.log('Starting process...');
if (process.env.NODE_ENV === 'development') {
  console.log('development mode');
  // Development-specific logic
}
if (process.env.NODE_ENV === 'production') {
  console.log('production mode');
  // Production-specific logic
}

console.log('Process started.');


// // Sử dụng function để đóng gói logic và giảm lặp code

const startProcess = () => {
    const env = process.env.NODE_ENV;

    console.log('Starting process...');

    if (env === 'development') {
      console.log('development mode');
      // Development-specific logic
    }

    if (env === 'production') {
      console.log('production mode');
      // Production-specific logic
    }

    console.log('Process started.');
}