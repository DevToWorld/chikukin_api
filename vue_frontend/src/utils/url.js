// Utilities to resolve media URLs for <img src>
// Keeps design stable by not forcing API base for frontend assets

export function resolveMediaUrl(input) {
  if (!input) return ''
  const url = String(input)
  const lower = url.toLowerCase()

  // Absolute URLs and protocol-relative
  if (lower.startsWith('http://') || lower.startsWith('https://') || url.startsWith('//')) {
    return url
  }

  // Storage URLs
  if (url.startsWith('/storage/')) return url
  if (url.startsWith('storage/')) return `/${url}`

  // Heuristic: admin uploads for the media registry often name files like
  // "media-<key>-<timestamp>.<ext>" (when pageKey is "media") or
  // "company-profile-<key>-<timestamp>.<ext>" (when pageKey is "company-profile").
  // If a bare filename (no slash) slips through, map it to the expected
  // public disk location so it won't 404 on the frontend.
  // Examples:
  //   media-company_profile_staff_mizokami-1757837975.svg
  //   company-profile-company_profile_message-1757855317.svg
  if (!url.includes('/') && /\.(png|jpe?g|webp|gif|svg)$/i.test(url)) {
    if (lower.startsWith('media-')) {
      return `/storage/pages/media/${url}`
    }
    if (lower.startsWith('company-profile-')) {
      return `/storage/pages/company-profile/${url}`
    }
    // Fallback: assume it's on public disk root
    return `/storage/${url}`
  }

  // Frontend-bundled assets
  if (url.startsWith('/img/') || url.startsWith('/images/') || url.startsWith('/assets/') || url.startsWith('/favicon')) {
    return url
  }

  // Other root-relative paths: leave as-is (resolved by current origin)
  if (url.startsWith('/')) return url

  // Fallback: assume public disk relative path (strip leading 'public/')
  const path = url.replace(/^public\//, '')
  return `/storage/${path}`
}

export default { resolveMediaUrl }
