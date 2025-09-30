import 'dart:async';
import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:sam_app/data/cache/image_cache_service.dart';

class CachedPostImage extends StatefulWidget {
  final String url;
  final double height;
  final double? width;
  final BorderRadius borderRadius;
  final VoidCallback onTap;
  final ImageCacheService imageCacheService;

  const CachedPostImage({
    super.key,
    required this.url,
    required this.height,
    this.width,
    required this.borderRadius,
    required this.onTap,
    required this.imageCacheService,
  });

  @override
  State<CachedPostImage> createState() => _CachedPostImageState();
}

class _CachedPostImageState extends State<CachedPostImage> {
  final Map<String, Timer?> _retryTimers = {};

  void _scheduleRetry(String url) {
    if (_retryTimers[url] != null) return;

    _retryTimers[url] = Timer(const Duration(milliseconds: 200), () {
      _retryTimers[url]?.cancel();
      _retryTimers[url] = null;
      widget.imageCacheService.removeFromCache(url);
      if (mounted) setState(() {});
    });
  }

  Future<Uint8List> _loadImageBytes(String url) {
    return widget.imageCacheService.fetchImageBytes(url);
  }

  @override
  void dispose() {
    for (final timer in _retryTimers.values) {
      timer?.cancel();
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Uint8List>(
      key: ValueKey(widget.url),
      future: _loadImageBytes(widget.url),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return SizedBox(
            height: widget.height,
            width: double.infinity,
            child: const Center(child: CircularProgressIndicator()),
          );
        }
        if (snapshot.hasError) {
          _scheduleRetry(widget.url);
          return SizedBox(
            height: widget.height,
            width: double.infinity,
            child: const Center(child: CircularProgressIndicator()),
          );
        }

        return GestureDetector(
          onTap: widget.onTap,
          child: ClipRRect(
            borderRadius: widget.borderRadius,
            child: Image.memory(
              snapshot.data!,
              height: widget.height,
              width: double.infinity,
              fit: BoxFit.cover,
            ),
          ),
        );
      },
    );
  }
}
