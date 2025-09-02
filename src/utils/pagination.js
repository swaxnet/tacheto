function getPaginationParams(query) {
  const page = Math.max(parseInt(query.page || '1', 10), 1);
  const pageSize = Math.min(Math.max(parseInt(query.pageSize || '10', 10), 5), 50);
  const offset = (page - 1) * pageSize;
  return { page, pageSize, offset };
}

function buildPageUrls(basePath, query, totalCount) {
  const { page, pageSize } = getPaginationParams(query);
  const totalPages = Math.max(Math.ceil(totalCount / pageSize), 1);
  const urlWith = (p) => {
    const params = new URLSearchParams({ ...query, page: String(p), pageSize: String(pageSize) });
    return `${basePath}?${params.toString()}`;
  };
  return {
    page,
    pageSize,
    totalPages,
    hasPrev: page > 1,
    hasNext: page < totalPages,
    prevUrl: page > 1 ? urlWith(page - 1) : null,
    nextUrl: page < totalPages ? urlWith(page + 1) : null,
  };
}

module.exports = { getPaginationParams, buildPageUrls }; 